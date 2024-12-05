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
        $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();

        $cartCount = $cartItems->sum('quantity');

        $cartTotal = $cartItems->sum(function ($item) {
            return ($item->product->sale_price ?? $item->product->price) * $item->quantity;
        });

        $promoDiscount = session('promo_discount', 0);

        $discountedTotal = $cartTotal * ((100 - $promoDiscount) / 100);

        return view('e-shop.cart', compact('cartItems', 'cartCount', 'cartTotal', 'discountedTotal', 'promoDiscount'));
    }


    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to add items to the cart.');
        }

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'size' => 'required|string',
        ]);

        $userId = Auth::id();

        $existingItem = Cart::where('user_id', $userId)
            ->where('product_id', $validated['product_id'])
            ->where('size', $validated['size'])
            ->first();

        if ($existingItem) {
            $existingItem->increment('quantity');
        } else {
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
            $cartItem->delete();

            $cartItems = Cart::where('user_id', Auth::id())->get();
            if ($cartItems->isEmpty()) {
                session()->forget('promo_discount');
            }

            return redirect()->route('cart.index')->with('success', 'Item removed from the cart.');
        }

        return redirect()->route('cart.index')->with('error', 'Item not found in the cart.');
    }

    public function applyPromo(Request $request)
    {
        $promoCode = $request->input('promo_code');

        if ($promoCode == 'DISCOUNT10') {
            $discount = 10;
            session(['promo_discount' => $discount]);
            return back()->with('success', 'Promo code applied successfully!');
        }

        return back()->with('error', 'Invalid promo code.');
    }

    public function removePromo()
    {
        session()->forget('promo_discount');
        return back()->with('success', 'Promo code removed successfully!');
    }



}
