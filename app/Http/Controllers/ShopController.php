<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Auth;
use Storage;

class ShopController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $sortOption = $request->input('sort', 'recommend'); // Default to 'recommend'
        switch ($sortOption) {
            case 'price-low-high':
                $products = Product::orderBy('price', 'asc')->get();
                break;
            case 'price-high-low':
                $products = Product::orderBy('price', 'desc')->get();
                break;
            default:
                $products = Product::orderBy('created_at', 'desc')->get();
                break;
        }
        return view('e-shop.shop', compact('products'));
    }
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('e-shop.create_product', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'product_number' => 'required|unique:products,product_number,' . $id,
            'name' => 'required',
            'price' => 'required|numeric',
            'sale_price' => 'nullable|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sizes' => 'required|array',
            'sizes.s' => 'required|numeric|min:0',
            'sizes.m' => 'required|numeric|min:0',
            'sizes.l' => 'required|numeric|min:0',
        ]);

        $data = $request->only(['product_number', 'name', 'price', 'sale_price', 'sizes']);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('shop.index')->with('success', 'Product updated successfully.');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('shop.index')->with('success', 'Product deleted successfully.');
    }
    public function paymentSuccess()
    {
        $userId = Auth::id();

        $cartItems = Cart::with('product')->where('user_id', $userId)->get();

        // Iterate through the cart items and update the product sizes
        foreach ($cartItems as $item) {
            $product = $item->product;
            $size = $item->size;
            $quantityPurchased = $item->quantity;

            // Check if the product sizes attribute is an array or JSON
            if (is_array($product->sizes) || is_object($product->sizes)) {
                $sizes = $product->sizes;

                if (isset($sizes[$size])) {
                    $sizes[$size] -= $quantityPurchased;

                    $sizes[$size] = max($sizes[$size], 0);

                    $product->sizes = $sizes;

                    $product->save();
                }
            }
        }

        // Delete cart items after successful payment
        Cart::where('user_id', $userId)->delete();

        // Remove the promo code from the session
        session()->forget('promo_discount');

        return redirect()->route('shop.index')->with('success', 'Payment successful! Your cart has been cleared, and the promo code has been removed.');
    }




    /**
     * Show the product creation page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        return view('e-shop.create_product');
    }

    /**
     * Store a newly created product in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate the product data
        $request->validate([
            'product_number' => 'required|unique:products',
            'name' => 'required',
            'price' => 'required|numeric',
            'sale_price' => 'nullable|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sizes' => 'required|array',
            'sizes.s' => 'required|numeric|min:0',
            'sizes.m' => 'required|numeric|min:0',
            'sizes.l' => 'required|numeric|min:0',
        ]);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images/products', 'public');
        }

        // Create the product in the database
        Product::create([
            'product_number' => $request->product_number,
            'name' => $request->name,
            'price' => $request->price,
            'sale_price' => $request->sale_price,
            'image' => $imagePath,
            'sizes' => $request->sizes,
        ]);

        return redirect()->route('shop.index')->with('success', 'Product added successfully.');
    }

}
