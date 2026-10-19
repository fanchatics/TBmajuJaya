<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');

        $orders = Order::with('user')
            ->withCount('items')
            ->when(array_key_exists($status, Order::STATUSES), fn ($q) => $q->where('status', $status))
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = $request->query('q');
                $q->where(function ($q) use ($term) {
                    $q->where('order_number', 'like', "%{$term}%")
                        ->orWhere('recipient_name', 'like', "%{$term}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $statusCounts = Order::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');

        return view('admin.orders.index', compact('orders', 'status', 'statusCounts'));
    }

    public function show(Order $order): View
    {
        $order->load(['items.product', 'user']);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(array_keys(Order::STATUSES))],
        ]);

        if ($order->status === Order::STATUS_CANCELLED) {
            return back()->with('error', 'Pesanan yang sudah dibatalkan tidak dapat diubah statusnya.');
        }

        if ($data['status'] === Order::STATUS_CANCELLED) {
            $order->cancelAndRestock();
        } else {
            $order->status = $data['status'];
            if ($data['status'] === Order::STATUS_PAID && ! $order->paid_at) {
                $order->paid_at = now();
            }
            $order->save();
        }

        return back()->with('success', "Status pesanan {$order->order_number} diubah menjadi {$order->fresh()->status_label}.");
    }
}
