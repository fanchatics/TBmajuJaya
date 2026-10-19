<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'sales' => (int) Order::whereIn('status', [
                Order::STATUS_PAID, Order::STATUS_PROCESSING, Order::STATUS_SHIPPED, Order::STATUS_COMPLETED,
            ])->sum('total_amount'),
            'orders' => Order::count(),
            'pending' => Order::where('status', Order::STATUS_PENDING)->count(),
            'products' => Product::count(),
            'customers' => User::where('role', User::ROLE_CUSTOMER)->count(),
        ];

        $recentOrders = Order::with('user')->latest()->take(8)->get();
        $lowStockProducts = Product::with('category')
            ->where('stock', '<=', Product::LOW_STOCK_THRESHOLD)
            ->orderBy('stock')
            ->take(8)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'lowStockProducts'));
    }
}
