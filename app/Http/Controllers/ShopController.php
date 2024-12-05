<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Auth;

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
    public function paymentSuccess()
    {
        // Get the current user ID
        $userId = Auth::id();

        // Get the user's cart items
        $cartItems = Cart::with('product')->where('user_id', $userId)->get();

        // Iterate through the cart items and update the product sizes
        foreach ($cartItems as $item) {
            $product = $item->product;
            $size = $item->size; // Assuming 'size' is one of 's', 'm', or 'l'
            $quantityPurchased = $item->quantity;

            // Check if the product sizes attribute is an array or JSON
            if (is_array($product->sizes) || is_object($product->sizes)) {
                // Get the current sizes array
                $sizes = $product->sizes;

                // Check if the specific size exists in the sizes array
                if (isset($sizes[$size])) {
                    // Reduce the quantity for the purchased size
                    $sizes[$size] -= $quantityPurchased;

                    // Ensure that the size quantity doesn't go below zero
                    $sizes[$size] = max($sizes[$size], 0);

                    // Update the sizes attribute on the model
                    $product->sizes = $sizes;

                    // Save the updated product back to the database
                    $product->save();
                }
            }
        }

        // Delete cart items after successful payment
        Cart::where('user_id', $userId)->delete();

        // Remove the promo code from the session
        session()->forget('promo_discount'); // Remove the promo code session value

        // Redirect with a success message
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
            'sizes.s' => 'required|numeric|min:0',  // Validate 'S' size
            'sizes.m' => 'required|numeric|min:0',  // Validate 'M' size
            'sizes.l' => 'required|numeric|min:0',  // Validate 'L' size
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
            'sizes' => $request->sizes, // Array of sizes and stock
        ]);

        // Redirect back with a success message
        return redirect()->route('shop.index')->with('success', 'Product added successfully.');
    }

}
