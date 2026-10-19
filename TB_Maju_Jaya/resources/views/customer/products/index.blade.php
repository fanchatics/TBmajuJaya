@extends('layouts.app')

@section('title', $activeCategory ? $activeCategory->name : 'Semua Produk')

@section('content')
<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
            <li class="breadcrumb-item active">{{ $activeCategory?->name ?? 'Semua Produk' }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        <aside class="col-lg-3">
            <div class="card-soft p-3">
                <h6 class="fw-bold text-navy mb-3">Kategori</h6>
                <div class="list-group list-group-flush">
                    <a href="{{ route('products.index', array_filter(['q' => $search, 'sort' => $sort])) }}"
                       class="list-group-item list-group-item-action d-flex justify-content-between px-2 {{ $activeCategory ? '' : 'active' }}">
                        Semua <span class="badge bg-light text-dark">{{ $categories->sum('products_count') }}</span>
                    </a>
                    @foreach ($categories as $category)
                        <a href="{{ route('products.index', array_filter(['category' => $category->slug, 'q' => $search, 'sort' => $sort])) }}"
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-2 {{ $activeCategory?->id === $category->id ? 'active' : '' }}">
                            <span><i class="bi {{ $category->icon }} me-2"></i>{{ $category->name }}</span>
                            <span class="badge bg-light text-dark">{{ $category->products_count }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </aside>

        <section class="col-lg-9">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <div>
                    <h1 class="h4 fw-bold text-navy mb-0">{{ $activeCategory?->name ?? 'Semua Produk' }}</h1>
                    <small class="text-muted">
                        {{ $products->total() }} produk ditemukan
                        @if ($search) untuk "<strong>{{ $search }}</strong>" @endif
                    </small>
                </div>
                <form method="get" class="d-flex align-items-center gap-2">
                    @if ($activeCategory) <input type="hidden" name="category" value="{{ $activeCategory->slug }}"> @endif
                    @if ($search) <input type="hidden" name="q" value="{{ $search }}"> @endif
                    <label class="small text-muted text-nowrap" for="sort">Urutkan</label>
                    <select name="sort" id="sort" class="form-select form-select-sm" data-autosubmit>
                        @foreach (['terbaru' => 'Terbaru', 'termurah' => 'Harga Terendah', 'termahal' => 'Harga Tertinggi', 'nama' => 'Nama A-Z'] as $value => $label)
                            <option value="{{ $value }}" @selected($sort === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </form>
            </div>

            @if ($products->isEmpty())
                <div class="card-soft p-5 text-center">
                    <i class="bi bi-search fs-1 text-muted"></i>
                    <h5 class="mt-3">Produk tidak ditemukan</h5>
                    <p class="text-muted">Coba kata kunci lain atau lihat semua kategori.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-accent">Lihat Semua Produk</a>
                </div>
            @else
                <div class="row g-3 row-cols-2 row-cols-md-3">
                    @foreach ($products as $product)
                        <div class="col">@include('customer.partials.product-card', ['product' => $product])</div>
                    @endforeach
                </div>
                <div class="mt-4 d-flex justify-content-center">{{ $products->links() }}</div>
            @endif
        </section>
    </div>
</div>
@endsection
