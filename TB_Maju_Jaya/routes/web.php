<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Customer;
use App\Http\Controllers\historyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// ---------------------------------------------------------------- Storefront (public)
Route::get('/', [Customer\HomeController::class, 'index'])->name('home');
Route::get('/produk', [Customer\ProductController::class, 'index'])->name('products.index');
Route::get('/produk/{product:slug}', [Customer\ProductController::class, 'show'])->name('products.show');

// ---------------------------------------------------------------- Authentication
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1')->name('login.attempt');
    Route::get('/daftar', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/daftar', [AuthController::class, 'register'])->middleware('throttle:10,1')->name('register.store');
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// ---------------------------------------------------------------- Customer (must be logged in)
Route::middleware('auth')->group(function () {
    Route::get('/keranjang', [Customer\CartController::class, 'index'])->name('cart.index');
    Route::post('/keranjang', [Customer\CartController::class, 'store'])->name('cart.store');
    Route::delete('/keranjang', [Customer\CartController::class, 'clear'])->name('cart.clear');
    Route::patch('/keranjang/{cart}', [Customer\CartController::class, 'update'])->whereNumber('cart')->name('cart.update');
    Route::delete('/keranjang/{cart}', [Customer\CartController::class, 'destroy'])->whereNumber('cart')->name('cart.destroy');

    Route::get('/checkout', [Customer\CheckoutController::class, 'create'])->name('checkout.create');
    Route::post('/checkout', [Customer\CheckoutController::class, 'store'])->name('checkout.store');

    Route::get('/pesanan', [Customer\OrderController::class, 'index'])->name('orders.index');
    Route::get('/pesanan/{order:order_number}', [Customer\OrderController::class, 'show'])->name('orders.show');
    Route::get('/pesanan/{order:order_number}/invoice', [Customer\OrderController::class, 'invoice'])->name('orders.invoice');
    Route::post('/pesanan/{order:order_number}/bukti-bayar', [Customer\OrderController::class, 'uploadProof'])->name('orders.proof');
    Route::post('/pesanan/{order:order_number}/batal', [Customer\OrderController::class, 'cancel'])->name('orders.cancel');

    Route::get('/profil', [Customer\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [Customer\ProfileController::class, 'update'])->name('profile.update');
});

// ---------------------------------------------------------------- Admin (auth + admin role)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('products', Admin\ProductController::class)->except('show');
    Route::resource('categories', Admin\CategoryController::class)->only(['index', 'store', 'update', 'destroy']);

    Route::get('/pesanan', [Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('/pesanan/{order:order_number}', [Admin\OrderController::class, 'show'])->name('orders.show');
    Route::patch('/pesanan/{order:order_number}/status', [Admin\OrderController::class, 'updateStatus'])->name('orders.status');

    Route::get('/pelanggan', [Admin\CustomerController::class, 'index'])->name('customers.index');
});

// ---------------------------------------------------------------- Legacy "history" pages (pre-existing, unchanged)
Route::get('/history', [historyController::class, 'index'])->name('history');
Route::get('/history/last', [historyController::class, 'last'])->name('histories.last');
Route::post('/history/search', [historyController::class, 'search'])->name('histories.search');
Route::post('/history/search_date', [historyController::class, 'search_date'])->name('histories.search_date');
