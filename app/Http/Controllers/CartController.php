<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Auth;
use App\Models\PromoCode;
class CartController extends Controller
{

    public function index()
    {
        // Get the current user's cart items
        $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();

        // Count the total number of items in the cart
        $cartCount = $cartItems->sum('quantity');  // Assuming you have 'quantity' in the cart model

        // Calculate the total cart value
        $cartTotal = $cartItems->sum(function ($item) {
            return ($item->product->sale_price ?? $item->product->price) * $item->quantity;
        });

        // Retrieve the promo discount (default is 0 if no promo code is applied)
        $promoDiscount = session('promo_discount', 0);

        // Calculate the discounted total
        $discountedTotal = $cartTotal * ((100 - $promoDiscount) / 100);

        // Return the view with the necessary variables
        return view('e-shop.cart', compact('cartItems', 'cartCount', 'cartTotal', 'discountedTotal', 'promoDiscount'));
    }


    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to add items to the cart.');
        }

        // Validate the request
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



    public function destroy($cartItemId)
    {
        $cartItem = Cart::find($cartItemId);
        if ($cartItem && $cartItem->user_id === Auth::id()) {
            // Remove the cart item
            $cartItem->delete();

            // Check if the cart is empty after removal
            $cartItems = Cart::where('user_id', Auth::id())->get();
            if ($cartItems->isEmpty()) {
                // If the cart is empty, remove the promo code from session
                session()->forget('promo_discount');
            }

            // Redirect back to the cart page with a success message
            return redirect()->route('cart.index')->with('success', 'Item removed from the cart.');
        }

        return redirect()->route('cart.index')->with('error', 'Item not found in the cart.');
    }

    public function applyPromo(Request $request)
    {
        $promoCode = $request->input('promo_code');

        // Check if the promo code is valid (this is a mock example)
        if ($promoCode == 'DISCOUNT10') {
            $discount = 10; // 10% discount
            session(['promo_discount' => $discount]); // Store discount in session
            return back()->with('success', 'Promo code applied successfully!');
        }

        return back()->with('error', 'Invalid promo code.');
    }

    public function removePromo()
    {
        // Remove the promo code from session
        session()->forget('promo_discount');
        return back()->with('success', 'Promo code removed successfully!');
    }



}
