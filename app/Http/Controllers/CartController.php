<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Auth;

class CartController extends Controller
{
    public function index()
    {
        // Get the current user's cart items
        $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();

        // Count the total number of items in the cart
        $cartCount = $cartItems->sum('quantity');  // Assuming you have 'quantity' in the cart model

        // Return the view with the cart count and items
        return view('e-shop.cart', compact('cartItems', 'cartCount'))->with('cartCount', $cartCount);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'size' => 'required|string',
        ]);

        $userId = Auth::id();

        // Check if the item is already in the cart
        $existingItem = Cart::where('user_id', $userId)
            ->where('product_id', $validated['product_id'])
            ->where('size', $validated['size'])
            ->first();

        if ($existingItem) {
            // Increment quantity if item already exists
            $existingItem->increment('quantity');
        } else {
            // Create a new cart item
            Cart::create([
                'user_id' => $userId,
                'product_id' => $validated['product_id'],
                'size' => $validated['size'],
                'quantity' => 1,
            ]);
        }

        $cartCount = Cart::where('user_id', $userId)->sum('quantity');

        return redirect()->route('cart.index');
    }


    public function destroy($id)
    {
        $cartItem = Cart::findOrFail($id);
        $cartItem->delete();

        $cartCount = Cart::where('user_id', Auth::id())->sum('quantity');

        return redirect()->route('cart.index');
    }

}
