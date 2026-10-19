@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index', ['category' => $product->category->slug]) }}">{{ $product->category->name }}</a></li>
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        <div class="col-md-5">
            <div class="zoom-box"><img src="{{ $product->image_url }}" alt="{{ $product->name }}"></div>
            <small class="text-muted d-block mt-2"><i class="bi bi-zoom-in me-1"></i>Arahkan kursor untuk memperbesar gambar</small>
        </div>

        <div class="col-md-7">
            <a href="{{ route('products.index', ['category' => $product->category->slug]) }}" class="badge bg-light text-navy border mb-2">
                <i class="bi {{ $product->category->icon }} me-1"></i>{{ $product->category->name }}
            </a>
            <h1 class="h3 fw-bold text-navy">{{ $product->name }}</h1>

            <div class="price display-6 my-3">@rupiah($product->price) <small class="fs-6">/ {{ $product->unit }}</small></div>

            <div class="mb-3">
                @if (! $product->in_stock)
                    <span class="badge bg-secondary fs-6">Stok Habis</span>
                @elseif ($product->is_low_stock)
                    <span class="badge bg-warning text-dark fs-6">Stok Menipis</span>
                    <span class="ms-2 text-muted">Sisa {{ $product->stock }} {{ $product->unit }}</span>
                @else
                    <span class="badge bg-success fs-6">Tersedia</span>
                    <span class="ms-2 text-muted">Stok {{ $product->stock }} {{ $product->unit }}</span>
                @endif
            </div>

            <form method="post" action="{{ route('cart.store') }}" data-cart-form class="mb-4">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <div class="d-flex flex-wrap align-items-center gap-3">
                    <div class="qty-picker">
                        <button type="button" data-qty-step="-1" aria-label="Kurangi">&minus;</button>
                        <input type="number" name="quantity" value="1" min="1" max="{{ max($product->stock, 1) }}" aria-label="Jumlah">
                        <button type="button" data-qty-step="1" aria-label="Tambah">+</button>
                    </div>
                    <button type="submit" class="btn btn-accent btn-lg px-4" @disabled(! $product->in_stock)>
                        <i class="bi bi-cart-plus me-1"></i>Tambah ke Keranjang
                    </button>
                </div>
            </form>

            <div class="card-soft p-3">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-desc" type="button">Deskripsi</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-spec" type="button">Spesifikasi</button></li>
                </ul>
                <div class="tab-content pt-3">
                    <div class="tab-pane fade show active" id="tab-desc">
                        {!! nl2br(e($product->description ?: 'Belum ada deskripsi untuk produk ini.')) !!}
                    </div>
                    <div class="tab-pane fade" id="tab-spec">
                        <table class="table table-sm mb-0">
                            <tr><th class="w-25 text-muted fw-normal">Kategori</th><td>{{ $product->category->name }}</td></tr>
                            <tr><th class="text-muted fw-normal">Satuan</th><td>{{ $product->unit }}</td></tr>
                            <tr><th class="text-muted fw-normal">Harga per {{ $product->unit }}</th><td>@rupiah($product->price)</td></tr>
                            <tr><th class="text-muted fw-normal">Stok</th><td>{{ $product->stock }} {{ $product->unit }}</td></tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($relatedProducts->isNotEmpty())
        <h2 class="section-title h5 mt-5 mb-3">Produk Terkait</h2>
        <div class="h-scroll">
            @foreach ($relatedProducts as $related)
                <div>@include('customer.partials.product-card', ['product' => $related])</div>
            @endforeach
        </div>
    @endif
</div>
@endsection
