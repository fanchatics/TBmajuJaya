<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::withCount('products')->orderBy('name')->get();
        $activeCategory = $request->filled('category')
            ? $categories->firstWhere('slug', $request->query('category'))
            : null;

        $query = Product::with('category')
            ->search($request->query('q'))
            ->inCategory($activeCategory?->id);

        match ($request->query('sort')) {
            'termurah' => $query->orderBy('price'),
            'termahal' => $query->orderByDesc('price'),
            'nama' => $query->orderBy('name'),
            default => $query->latest(),
        };

        $products = $query->paginate(12)->withQueryString();

        return view('customer.products.index', [
            'products' => $products,
            'categories' => $categories,
            'activeCategory' => $activeCategory,
            'search' => $request->query('q'),
            'sort' => $request->query('sort', 'terbaru'),
        ]);
    }

    public function show(Product $product): View
    {
        $product->load('category');

        $relatedProducts = Product::with('category')
            ->where('category_id', $product->category_id)
            ->whereKeyNot($product->id)
            ->inRandomOrder()
            ->take(8)
            ->get();

        return view('customer.products.show', compact('product', 'relatedProducts'));
    }
}
