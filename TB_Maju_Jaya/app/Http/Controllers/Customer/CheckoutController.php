<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        $user = $request->user();
        $carts = $user->carts()->with('product')->get();

        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')->with('warning', 'Keranjang masih kosong.');
        }

        $total = $carts->sum(fn (Cart $cart) => $cart->subtotal);
        $bankAccounts = config('shop.bank_accounts');

        return view('customer.checkout', compact('user', 'carts', 'total', 'bankAccounts'));
    }

    public function store(CheckoutRequest $request): RedirectResponse
    {
        $user = $request->user();
        $carts = $user->carts()->with('product')->get();

        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')->with('warning', 'Keranjang masih kosong.');
        }

        $order = DB::transaction(function () use ($request, $user, $carts) {
            // Re-read stock inside the transaction so we validate against the freshest numbers.
            $products = Product::whereIn('id', $carts->pluck('product_id'))->lockForUpdate()->get()->keyBy('id');

            foreach ($carts as $cart) {
                $product = $products[$cart->product_id];
                if ($product->stock < $cart->quantity) {
                    abort(redirect()->route('cart.index')->with(
                        'error',
                        "Stok {$product->name} tidak mencukupi (tersisa {$product->stock} {$product->unit}). Silakan sesuaikan jumlahnya."
                    ));
                }
            }

            $order = Order::create([
                'order_number' => Order::generateNumber(),
                'user_id' => $user->id,
                'total_amount' => $carts->sum(fn (Cart $cart) => $products[$cart->product_id]->price * $cart->quantity),
                'status' => Order::STATUS_PENDING,
                'recipient_name' => $request->validated('recipient_name'),
                'phone' => $request->validated('phone'),
                'shipping_address' => $request->validated('shipping_address'),
                'notes' => $request->validated('notes'),
            ]);

            foreach ($carts as $cart) {
                $product = $products[$cart->product_id];

                $order->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'unit' => $product->unit,
                    'price' => $product->price,
                    'quantity' => $cart->quantity,
                    'subtotal' => $product->price * $cart->quantity,
                ]);

                $product->decrement('stock', $cart->quantity);
            }

            $user->carts()->delete();

            return $order;
        });

        return redirect()->route('orders.show', $order)
            ->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran dan unggah bukti transfer.');
    }
}
