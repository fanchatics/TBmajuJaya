@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
<div class="container mt-4">
    {{-- Hero --}}
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            @foreach (range(0, 2) as $i)
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $i }}" @class(['active' => $i === 0])></button>
            @endforeach
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="hero-slide hero-1">
                    <div style="max-width:560px;position:relative;z-index:1">
                        <span class="hero-badge mb-3">KUALITAS TERBAIK</span>
                        <h1 class="display-6 mb-3">Semua Kebutuhan Bangun Rumah, Dalam Satu Toko.</h1>
                        <p class="mb-4 text-white-50">Semen, pipa, cat, baja ringan hingga perkakas — lengkap, original, dan siap antar.</p>
                        <a href="{{ route('products.index') }}" class="btn btn-accent btn-lg">Belanja Sekarang <i class="bi bi-arrow-right ms-1"></i></a>
                    </div>
                    <i class="bi bi-bricks hero-icon"></i>
                </div>
            </div>
            <div class="carousel-item">
                <div class="hero-slide hero-2">
                    <div style="max-width:560px;position:relative;z-index:1">
                        <span class="hero-badge mb-3" style="background:#fff">PROMO BAJA RINGAN</span>
                        <h1 class="display-6 mb-3">Rangka Atap Kuat, Anti Karat & Anti Rayap.</h1>
                        <p class="mb-4 text-white-50">Baja ringan C75 dan reng dengan harga bersaing untuk proyek besar maupun kecil.</p>
                        <a href="{{ route('products.index', ['category' => 'baja-atap']) }}" class="btn btn-light btn-lg fw-semibold">Lihat Baja & Atap</a>
                    </div>
                    <i class="bi bi-building hero-icon"></i>
                </div>
            </div>
            <div class="carousel-item">
                <div class="hero-slide hero-3">
                    <div style="max-width:560px;position:relative;z-index:1">
                        <span class="hero-badge mb-3">PENGIRIMAN CEPAT</span>
                        <h1 class="display-6 mb-3">Pesan dari Rumah, Material Sampai ke Lokasi.</h1>
                        <p class="mb-4 text-white-50">Gratis konsultasi kebutuhan material. Pengiriman radius {{ config('shop.delivery_radius_km') }} km dari toko.</p>
                        <a href="https://wa.me/{{ config('shop.whatsapp') }}" target="_blank" rel="noopener" class="btn btn-accent btn-lg"><i class="bi bi-whatsapp me-1"></i> Tanya via WhatsApp</a>
                    </div>
                    <i class="bi bi-truck hero-icon"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Highlights --}}
    <div class="row g-3 mt-3">
        @foreach ([
            ['bi-shield-check', 'Garansi Kualitas', 'Produk original & bergaransi'],
            ['bi-truck', 'Pengiriman Cepat', 'Antar sampai lokasi proyek'],
            ['bi-tags', 'Harga Bersaing', 'Harga langsung dari distributor'],
        ] as [$icon, $title, $desc])
            <div class="col-md-4">
                <div class="feature-box">
                    <div class="ico"><i class="bi {{ $icon }}"></i></div>
                    <div><div class="fw-bold text-navy">{{ $title }}</div><small class="text-muted">{{ $desc }}</small></div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Categories --}}
    <div class="d-flex justify-content-between align-items-end mt-5 mb-3">
        <h2 class="section-title h4 mb-0">Kategori Produk</h2>
        <a href="{{ route('products.index') }}" class="small fw-semibold">Lihat semua <i class="bi bi-arrow-right"></i></a>
    </div>
    <div class="row g-3 row-cols-2 row-cols-md-3 row-cols-lg-6">
        @foreach ($categories as $category)
            <div class="col">
                <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="cat-card">
                    <div class="ico"><i class="bi {{ $category->icon }}"></i></div>
                    <div class="fw-semibold small">{{ $category->name }}</div>
                    <small>{{ $category->products_count }} produk</small>
                </a>
            </div>
        @endforeach
    </div>

    {{-- Products --}}
    <div class="d-flex justify-content-between align-items-end mt-5 mb-3">
        <h2 class="section-title h4 mb-0">Produk Terbaru</h2>
        <a href="{{ route('products.index') }}" class="small fw-semibold">Lihat semua <i class="bi bi-arrow-right"></i></a>
    </div>
    @if ($products->isEmpty())
        <div class="card-soft p-5 text-center text-muted">Belum ada produk.</div>
    @else
        <div class="row g-3 row-cols-2 row-cols-md-3 row-cols-lg-4">
            @foreach ($products as $product)
                <div class="col">@include('customer.partials.product-card', ['product' => $product])</div>
            @endforeach
        </div>
    @endif
</div>
@endsection
