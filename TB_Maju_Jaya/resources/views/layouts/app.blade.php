<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TB Maju Jaya') — {{ config('shop.tagline') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/shop.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body data-login-url="{{ route('login') }}">

<div class="topbar d-none d-md-block">
    <div class="container d-flex justify-content-between py-1">
        <span><i class="bi bi-geo-alt-fill text-warning me-1"></i>{{ config('shop.address') }}</span>
        <span>
            <i class="bi bi-truck text-warning me-1"></i>Pengiriman radius {{ config('shop.delivery_radius_km') }} km
            <span class="mx-2">|</span>
            <a href="https://wa.me/{{ config('shop.whatsapp') }}" target="_blank" rel="noopener"><i class="bi bi-whatsapp text-warning me-1"></i>Hubungi Kami</a>
        </span>
    </div>
</div>

<header class="site-header">
    <div class="container py-2">
        <div class="d-flex align-items-center gap-3 flex-wrap flex-lg-nowrap">
            <a href="{{ route('home') }}" class="brand me-lg-3">
                <span class="brand-mark"><i class="bi bi-hammer"></i></span>
                <span>TB MAJU JAYA<small>TOKO BANGUNAN</small></span>
            </a>

            <form action="{{ route('products.index') }}" method="get" class="flex-grow-1 order-3 order-lg-2 w-100" role="search">
                <div class="input-group search-group">
                    <select name="category" class="form-select" aria-label="Kategori">
                        <option value="">Semua Kategori</option>
                        @foreach ($navCategories as $navCategory)
                            <option value="{{ $navCategory->slug }}" @selected(request('category') === $navCategory->slug)>{{ $navCategory->name }}</option>
                        @endforeach
                    </select>
                    <input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari semen, pipa, cat, paku...">
                    <button class="btn btn-accent" type="submit" aria-label="Cari"><i class="bi bi-search"></i></button>
                </div>
            </form>

            <div class="d-flex align-items-center gap-2 ms-auto order-2 order-lg-3">
                <a href="{{ route('cart.index') }}" class="cart-link" aria-label="Keranjang">
                    <i class="bi bi-cart3"></i>
                    <span id="cartBadge" class="cart-badge {{ $cartCount ? '' : 'd-none' }}">{{ $cartCount }}</span>
                </a>

                @auth
                    <div class="dropdown">
                        <button class="btn btn-light d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle fs-5 text-navy"></i>
                            <span class="d-none d-md-inline fw-semibold">{{ Str::limit(auth()->user()->name, 14) }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            @if (auth()->user()->isAdmin())
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard Admin</a></li>
                            @endif
                            <li><a class="dropdown-item" href="{{ route('orders.index') }}"><i class="bi bi-receipt me-2"></i>Pesanan Saya</a></li>
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person-gear me-2"></i>Profil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="post" action="{{ route('logout') }}">@csrf
                                    <button class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Keluar</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-primary d-none d-sm-inline-block">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-accent">Daftar</a>
                @endauth
            </div>
        </div>
    </div>
</header>

@include('partials.alerts')

<main>@yield('content')</main>

<footer class="site-footer mt-5">
    <div class="container py-5">
        <div class="row g-4">
            <div class="col-md-5">
                <div class="brand text-white mb-3"><span class="brand-mark"><i class="bi bi-hammer"></i></span><span>TB MAJU JAYA</span></div>
                <p class="mb-1">{{ config('shop.tagline') }}.</p>
                <p class="small mb-0">Lengkap untuk kebutuhan bangun & renovasi rumah Anda. Pengiriman untuk pembeli dengan radius {{ config('shop.delivery_radius_km') }} km dari toko.</p>
            </div>
            <div class="col-6 col-md-3">
                <h6 class="mb-3">Belanja</h6>
                <ul class="list-unstyled small mb-0">
                    <li class="mb-1"><a href="{{ route('products.index') }}">Semua Produk</a></li>
                    @foreach ($navCategories->take(4) as $navCategory)
                        <li class="mb-1"><a href="{{ route('products.index', ['category' => $navCategory->slug]) }}">{{ $navCategory->name }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="col-6 col-md-4">
                <h6 class="mb-3">Kontak</h6>
                <ul class="list-unstyled small mb-0">
                    <li class="mb-1"><i class="bi bi-geo-alt me-2"></i>{{ config('shop.address') }}</li>
                    <li class="mb-1"><i class="bi bi-whatsapp me-2"></i>+{{ config('shop.whatsapp') }}</li>
                </ul>
            </div>
        </div>
        <hr class="border-secondary my-4">
        <p class="small text-center mb-0">&copy; {{ date('Y') }} TB Maju Jaya. All rights reserved.</p>
    </div>
</footer>

<a href="https://wa.me/{{ config('shop.whatsapp') }}" class="wa-float" target="_blank" rel="noopener" aria-label="WhatsApp"><i class="bi bi-whatsapp"></i></a>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script src="{{ asset('js/shop.js') }}"></script>
@stack('scripts')
</body>
</html>
