<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\historyController;

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

Route::get('/', function () {
    return view('homepage');
});


Route::get('/history', [historyController::class, 'index'])->name('history');
Route::get('/history/last', [historyController::class, 'last'])->name('histories.last');
Route::post('/history/search', [historyController::class, 'search'])->name('histories.search');
Route::post('/history/search_date', [historyController::class, 'search_date'])->name('histories.search_date');
