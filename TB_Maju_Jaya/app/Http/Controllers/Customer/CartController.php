<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(Request $request): View
    {
        $carts = $request->user()->carts()->with('product.category')->get();
        $total = $carts->sum(fn (Cart $cart) => $cart->subtotal);

        return view('customer.cart', compact('carts', 'total'));
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:9999'],
        ]);

        $product = Product::findOrFail($data['product_id']);
        $quantity = $data['quantity'] ?? 1;

        if (! $product->in_stock) {
            return $this->respond($request, false, "Stok {$product->name} sedang habis.");
        }

        $cart = Cart::firstOrNew(['user_id' => $request->user()->id, 'product_id' => $product->id]);
        $wanted = ($cart->exists ? $cart->quantity : 0) + $quantity;
        $cart->quantity = min($wanted, $product->stock);
        $cart->save();

        $message = $wanted > $product->stock
            ? "Jumlah disesuaikan dengan stok tersedia ({$product->stock} {$product->unit})."
            : "{$product->name} ditambahkan ke keranjang.";

        return $this->respond($request, true, $message);
    }

    public function update(Request $request, int $cart): RedirectResponse
    {
        $data = $request->validate(['quantity' => ['required', 'integer', 'min:1', 'max:9999']]);

        $item = $request->user()->carts()->with('product')->findOrFail($cart);
        $item->quantity = min($data['quantity'], max($item->product->stock, 1));
        $item->save();

        $message = $data['quantity'] > $item->product->stock
            ? "Jumlah disesuaikan dengan stok tersedia ({$item->product->stock} {$item->product->unit})."
            : 'Keranjang diperbarui.';

        return back()->with($data['quantity'] > $item->product->stock ? 'warning' : 'success', $message);
    }

    public function destroy(Request $request, int $cart): RedirectResponse
    {
        $request->user()->carts()->findOrFail($cart)->delete();

        return back()->with('success', 'Produk dihapus dari keranjang.');
    }

    public function clear(Request $request): RedirectResponse
    {
        $request->user()->carts()->delete();

        return back()->with('success', 'Keranjang dikosongkan.');
    }

    private function respond(Request $request, bool $ok, string $message): RedirectResponse|JsonResponse
    {
        if ($request->expectsJson()) {
            return response()->json([
                'ok' => $ok,
                'message' => $message,
                'count' => (int) Cart::where('user_id', $request->user()->id)->sum('quantity'),
            ], $ok ? 200 : 422);
        }

        return back()->with($ok ? 'success' : 'error', $message);
    }
}
