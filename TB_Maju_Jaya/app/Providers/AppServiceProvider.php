<?php

namespace App\Providers;

use App\Models\Cart;
use App\Models\Category;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        // @rupiah($value) => "Rp 3.000"
        Blade::directive('rupiah', function ($expression) {
            return "<?php echo 'Rp ' . number_format((float) ({$expression}), 0, ',', '.'); ?>";
        });

        // Data the storefront layout always needs, supplied once so no page can forget it.
        View::composer('layouts.app', function ($view) {
            $view->with('navCategories', Category::orderBy('name')->get());
            $view->with('cartCount', Auth::check() ? (int) Cart::where('user_id', Auth::id())->sum('quantity') : 0);
        });
    }
}
