<?php

namespace App\Http\Controllers;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function add(Request $request, $productId)
    {
        $user = Auth::user();

        $cart = Cart::firstOrCreate([
            'user_id' => $user->id
        ]);

        $volume = $request->input('volume');

        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->where('volume', $volume)
            ->first();

        if ($item) {
            $item->quantity += 1;
            $item->save();
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'quantity' => 1,
                'volume' => $volume,
            ]);
        }

        // compute new cart count (sum of quantities)
        $cart = Cart::where('user_id', $user->id)->with('items')->first();
        $count = $cart ? $cart->items->sum('quantity') : 0;

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['count' => $count]);
        }

        return redirect('/cart');
    }

    public function viewCart()
    {
        $user = auth()->user();

        // Lấy cart của user
        $cart = \App\Models\Cart::where('user_id', $user->id)->first();

        // Nếu chưa có cart → tránh lỗi
        if (!$cart) {
            return view('cart.index', [
                'cart' => null,
                'cartItems' => collect(),
                'total' => 0
            ]);
        }

        // Lấy items + load product
        $cartItems = $cart->items()->with('product')->get();

        // Remove any items that reference a missing product (product deleted)
        $cartItems = $cartItems->filter(function ($item) use ($cart) {
            if (! $item->product) {
                // cleanup orphaned cart items
                try {
                    $item->delete();
                } catch (\Throwable $e) {
                    // ignore deletion errors
                }
                return false;
            }
            return true;
        })->values();

        // Tính total (guard product->price)
        $total = $cartItems->sum(function ($item) {
            $price = $item->product->price ?? 0;
            return $price * $item->quantity;
        });

        return view('cart.index', compact('cart', 'cartItems', 'total'));
    }

}