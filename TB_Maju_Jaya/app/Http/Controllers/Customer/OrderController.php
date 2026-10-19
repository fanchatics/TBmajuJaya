<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class OrderController extends Controller
{
    /** Tab key => [label, statuses it contains] */
    public const TABS = [
        'semua' => ['Semua', null],
        'menunggu' => ['Menunggu Pembayaran', [Order::STATUS_PENDING]],
        'diproses' => ['Diproses', [Order::STATUS_PAID, Order::STATUS_PROCESSING]],
        'dikirim' => ['Dikirim', [Order::STATUS_SHIPPED]],
        'selesai' => ['Selesai', [Order::STATUS_COMPLETED]],
    ];

    public function index(Request $request): View
    {
        $tab = array_key_exists($request->query('tab'), self::TABS) ? $request->query('tab') : 'semua';

        $query = $request->user()->orders()->withCount('items')->latest();

        if ($statuses = self::TABS[$tab][1]) {
            $query->whereIn('status', $statuses);
        }

        $orders = $query->paginate(10)->withQueryString();
        $tabs = self::TABS;

        return view('customer.orders.index', compact('orders', 'tab', 'tabs'));
    }

    public function show(Request $request, Order $order): View
    {
        $this->ensureOwner($request, $order);
        $order->load('items.product');
        $bankAccounts = config('shop.bank_accounts');

        return view('customer.orders.show', compact('order', 'bankAccounts'));
    }

    public function invoice(Request $request, Order $order): View
    {
        $this->ensureOwner($request, $order);
        $order->load(['items', 'user']);

        return view('customer.orders.invoice', compact('order'));
    }

    public function uploadProof(Request $request, Order $order): RedirectResponse
    {
        $this->ensureOwner($request, $order);

        if ($order->status !== Order::STATUS_PENDING) {
            return back()->with('error', 'Bukti pembayaran hanya dapat diunggah untuk pesanan yang menunggu pembayaran.');
        }

        $request->validate(
            ['payment_proof' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072']],
            [],
            ['payment_proof' => 'bukti pembayaran']
        );

        if ($order->payment_proof) {
            Storage::disk('public')->delete($order->payment_proof);
        }

        $order->update(['payment_proof' => $request->file('payment_proof')->store('payment-proofs', 'public')]);

        return back()->with('success', 'Bukti pembayaran terkirim. Admin akan memverifikasi pesanan Anda.');
    }

    public function cancel(Request $request, Order $order): RedirectResponse
    {
        $this->ensureOwner($request, $order);

        if (! $order->canBeCancelled()) {
            return back()->with('error', 'Pesanan ini tidak dapat dibatalkan lagi.');
        }

        $order->cancelAndRestock();

        return back()->with('success', 'Pesanan dibatalkan.');
    }

    private function ensureOwner(Request $request, Order $order): void
    {
        abort_unless($order->user_id === $request->user()->id, 404);
    }
}
