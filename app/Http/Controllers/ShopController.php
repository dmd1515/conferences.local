<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Cart;
use App\Models\Product;
use Auth;
use Storage;

class ShopController extends Controller
{
    /**
         /** Messages are now loaded from config/messages.php */
    // Message constants now loaded from config/messages.php = 'Product updated successfully.';

    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }

    /**
     * Product listing + sorting
     */
    public function index(Request $request)
    {
        $sortOption = $request->input('sort', 'recommend');

        $products = match ($sortOption) {
            'price-low-high' => Product::orderBy('price', 'asc')->get(),
            'price-high-low' => Product::orderBy('price', 'desc')->get(),
            default => Product::orderBy('created_at', 'desc')->get(),
        };

        return view('e-shop.shop', compact('products'));
    }

    /**
     * Edit product page
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('e-shop.create_product', compact('product'));
    }

    /**
     * Update product
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $data = $this->validateProduct($request, $id);

        // Image handling
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('shop.index')->with('success', config('messages.product_updated'));
    }

    /**
     * Delete product
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        $this->deleteProductImage($product);

        $product->delete();

        return redirect()
            ->route('shop.index')
            ->with('success', config('messages.product_deleted'));
    }

    private function deleteProductImage(Product $product): void
    {
        if (!$product->image) {
            return;
        }

        Storage::disk('public')->delete($product->image);
    }


    /**
     * Payment success handler
     */
    public function paymentSuccess()
    {
        $userId = Auth::id();

        $this->updateProductStock($userId);
        $this->clearUserCart($userId);
        $this->clearPromoSession();

        return redirect()->route('shop.index')->with('success', config('messages.payment_success'));
    }

    /**
     * Update product stock
     */
    private function updateProductStock($userId)
    {
        $cartItems = Cart::with('product')->where('user_id', $userId)->get();

        foreach ($cartItems as $item) {
            $product = $item->product;
            $size = $item->size;
            $quantity = $item->quantity;

            if (!is_array($product->sizes) && !is_object($product->sizes)) {
                continue;
            }

            $sizes = $product->sizes;

            if (isset($sizes[$size])) {
                $sizes[$size] = max($sizes[$size] - $quantity, 0);
                $product->sizes = $sizes;
                $product->save();
            }
        }
    }

    /**
     * Clear user cart
     */
    private function clearUserCart($userId)
    {
        Cart::where('user_id', $userId)->delete();
    }

    /**
     * Clear promo session
     */
    private function clearPromoSession()
    {
        session()->forget('promo_discount');
    }

    /**
     * Create product page
     */
    public function create()
    {
        return view('e-shop.create_product');
    }

    /**
     * Store product
     */
    public function store(Request $request)
    {
        $data = $this->validateProduct($request);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('shop.index')->with('success', config('messages.product_added'));
    }

    /**
     * Central validator
     */
    private function validateProduct(Request $request, $id = null)
    {
        return $request->validate([
            'product_number' => [
                'required',
                Rule::unique('products', 'product_number')->ignore($id),
            ],
            'name' => 'required',
            'price' => 'required|numeric',
            'sale_price' => 'nullable|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sizes' => 'required|array',
            'sizes.s' => 'required|numeric|min:0',
            'sizes.m' => 'required|numeric|min:0',
            'sizes.l' => 'required|numeric|min:0',
        ]);
    }
}
