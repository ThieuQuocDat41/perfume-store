<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Cart;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // Danh sách đơn
    public function index(Request $request)
    {
        $orders = Order::where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return view('orders.index', compact('orders'));
    }

    // Chi tiết đơn
    public function show($id, Request $request)
    {
        // DEMO: choose behavior based on security_mode
        $mode = config('app.security_mode', env('APP_SECURITY_MODE', 'secure'));

        if ($mode === 'vulnerable') {
            // DEMO: vulnerable mode — NO ownership check (IDOR)
            $order = Order::findOrFail($id);
        } else {
            // DEMO: secure mode — enforce ownership
            $order = Order::where('id', $id)
                ->where('user_id', $request->user()->id) // 🔒 chống IDOR
                ->firstOrFail();
        }

        return view('orders.show', compact('order'));
    }

    // Checkout: convert current user's cart into an order
    // Step 1: Confirm selected cart items and allow editing recipient info
    public function confirm(Request $request)
    {
        $user = $request->user();

        $selected = $request->input('selected', []);

        $cart = Cart::where('user_id', $user->id)->with('items.product')->first();

        if (! $cart || $cart->items->isEmpty()) {
            return redirect('/cart')->with('status', 'Your cart is empty.');
        }

        // if none selected, default to all
        if (empty($selected)) {
            $items = $cart->items;
        } else {
            $items = $cart->items->whereIn('id', $selected)->values();
        }

        if ($items->isEmpty()) {
            return redirect('/cart')->with('error', 'No items selected for checkout.');
        }

        // Check existence and stock
        $insufficient = [];
        foreach ($items as $item) {
            $product = $item->product ?? Product::find($item->product_id);
            if (! $product) {
                $insufficient[] = "Product ID {$item->product_id} not found";
                continue;
            }
            if (isset($product->stock) && $product->stock < $item->quantity) {
                $insufficient[] = "{$product->name} has only {$product->stock} items available";
            }
        }

        if (! empty($insufficient)) {
            return redirect('/cart')->with('error', 'Cannot checkout: ' . implode('; ', $insufficient));
        }

        $total = $items->sum(function ($it) {
            return $it->product->price * $it->quantity;
        });

        return view('orders.confirm', [
            'items' => $items,
            'total' => $total,
            'user' => $user,
        ]);
    }

    // Step 2: finalize the order (create Order and OrderItems)
    public function finalize(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'selected' => 'required|array|min:1',
            'selected.*' => 'integer',
            'recipient_name' => 'required|string|max:255',
            'recipient_phone' => 'required|string|max:30',
            'recipient_address' => 'required|string|max:1000',
            'payment_method' => 'required|string|max:50',
            'voucher_code' => 'nullable|string|max:100',
        ]);

        $selected = $request->input('selected');

        $cart = Cart::where('user_id', $user->id)->with('items.product')->first();

        if (! $cart) {
            return redirect('/cart')->with('error', 'Cart not found.');
        }

        $items = $cart->items->whereIn('id', $selected)->values();

        if ($items->isEmpty()) {
            return redirect('/cart')->with('error', 'No valid items selected.');
        }

        // DEMO: choose behavior based on security_mode
        $mode = config('app.security_mode', env('APP_SECURITY_MODE', 'secure'));

        // compute total (secure: use DB price; vulnerable: may trust request->price)
        $total = 0;
        if ($mode === 'vulnerable' && $request->filled('price')) {
            // DEMO: vulnerable mode — trust client-supplied total when provided
            $clientTotal = $request->input('price');
            if (is_numeric($clientTotal) && $clientTotal >= 0) {
                $total = round(floatval($clientTotal), 2);
            } else {
                // fallback to DB prices if client total invalid
                foreach ($items as $item) {
                    $total += ($item->product->price * $item->quantity);
                }
            }
        } else {
            // DEMO: secure mode — compute from DB prices
            foreach ($items as $item) {
                $total += ($item->product->price * $item->quantity);
            }
        }

        $voucherCode = strtoupper(trim($request->input('voucher_code', '')));
        $appliedVoucher = null;
        $originalTotal = $total;
        if (!empty($voucherCode)) {
            $v = \App\Models\Voucher::where('code', $voucherCode)->first();
            if (! $v) {
                return view('orders.confirm', [
                    'items' => $items,
                    'total' => $originalTotal,
                    'user' => $user,
                    'voucher_error' => 'Voucher không tồn tại.',
                    'voucher_code' => $voucherCode,
                ]);
            }

            if ($mode === 'vulnerable') {
                // DEMO: vulnerable mode — SKIP 'already used' check and do NOT create redemption
                // This allows reuse of the same voucher by the same user (for demo only).
                $discountPercent = (int) $v->percent;
                $total = round($total * (100 - $discountPercent) / 100, 2);
                $appliedVoucher = $v;
            } else {
                // DEMO: secure mode — enforce redemption check and record usage
                $used = \App\Models\VoucherRedemption::where('voucher_id', $v->id)->where('user_id', $user->id)->exists();
                if ($used) {
                    return view('orders.confirm', [
                        'items' => $items,
                        'total' => $originalTotal,
                        'user' => $user,
                        'voucher_error' => 'Bạn đã sử dụng voucher này trước đó.',
                        'voucher_code' => $voucherCode,
                    ]);
                }

                $discountPercent = (int) $v->percent;
                $total = round($total * (100 - $discountPercent) / 100, 2);
                $appliedVoucher = $v;
            }
        }

        $order = DB::transaction(function () use ($user, $cart, $items, $request, $total, $originalTotal, $appliedVoucher) {
            $orderData = [
                'user_id' => $user->id,
                'total_price' => $total,
                'original_total_price' => $originalTotal,
                'discount_percent' => $appliedVoucher ? $appliedVoucher->percent : null,
                'voucher_code' => $appliedVoucher ? $appliedVoucher->code : null,
                'status' => 'pending',
                'recipient_name' => $request->input('recipient_name'),
                'recipient_phone' => $request->input('recipient_phone'),
                'recipient_address' => $request->input('recipient_address'),
                'payment_method' => $request->input('payment_method', 'C.O.D'),
            ];

            $order = Order::create($orderData);

            foreach ($items as $item) {
                $prod = $item->product;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $prod->id,
                    'quantity' => $item->quantity,
                    'price' => $prod->price,
                    'volume' => $item->volume ?? null,
                ]);

                // decrement stock if available
                if (isset($prod->stock)) {
                    $prod->stock = max(0, $prod->stock - $item->quantity);
                    $prod->save();
                }

                // remove the cart item
                $item->delete();
            }

            // mark redemption record if voucher applied (only in secure mode)
            $mode = config('app.security_mode', env('APP_SECURITY_MODE', 'secure'));
            if ($appliedVoucher && $mode !== 'vulnerable') {
                \App\Models\VoucherRedemption::create([
                    'voucher_id' => $appliedVoucher->id,
                    'user_id' => $user->id,
                    'used_at' => now(),
                ]);
            }

            // if cart empty, remove it
            if ($cart->items()->count() === 0) {
                $cart->delete();
            }

            return $order;
        });

        return redirect()->route('orders.show', ['id' => $order->id])->with('status', 'Order placed.');
    }
}