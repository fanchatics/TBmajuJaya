<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Admin TB Maju Jaya</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/shop.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
@php
    $pendingCount = \App\Models\Order::where('status', \App\Models\Order::STATUS_PENDING)->count();
    $menu = [
        ['admin.dashboard', 'bi-speedometer2', 'Dashboard', 'admin.dashboard'],
        ['admin.products.index', 'bi-box-seam', 'Produk', 'admin.products.*'],
        ['admin.categories.index', 'bi-tags', 'Kategori', 'admin.categories.*'],
        ['admin.orders.index', 'bi-receipt-cutoff', 'Pesanan Masuk', 'admin.orders.*'],
        ['admin.customers.index', 'bi-people', 'Pelanggan', 'admin.customers.*'],
    ];
@endphp

<div class="admin-shell" id="adminShell">
    <aside class="sidebar">
        <a href="{{ route('admin.dashboard') }}" class="brand">
            <span class="brand-mark"><i class="bi bi-hammer"></i></span>
            <span>TB MAJU JAYA<small>ADMIN PANEL</small></span>
        </a>

        <nav class="nav flex-column flex-grow-1 mt-2">
            <div class="nav-label">Menu</div>
            @foreach ($menu as [$route, $icon, $label, $pattern])
                <a href="{{ route($route) }}" class="nav-link {{ request()->routeIs($pattern) ? 'active' : '' }}">
                    <i class="bi {{ $icon }}"></i><span>{{ $label }}</span>
                    @if ($route === 'admin.orders.index' && $pendingCount)
                        <span class="badge bg-warning text-dark ms-auto">{{ $pendingCount }}</span>
                    @endif
                </a>
            @endforeach
            <div class="nav-label">Lainnya</div>
            <a href="{{ route('home') }}" class="nav-link" target="_blank"><i class="bi bi-shop"></i><span>Lihat Toko</span></a>
        </nav>

        <form method="post" action="{{ route('logout') }}" class="border-top border-secondary border-opacity-25">@csrf
            <button class="nav-link w-100 border-0 bg-transparent text-start" style="color:#fca5a5"><i class="bi bi-box-arrow-right"></i><span>Logout</span></button>
        </form>
    </aside>

    <div class="admin-main">
        <div class="admin-top d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-light" id="sidebarToggle" aria-label="Toggle sidebar"><i class="bi bi-list fs-5"></i></button>
                <h1 class="h5 mb-0 fw-bold text-navy">@yield('title', 'Dashboard')</h1>
            </div>
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-person-circle fs-4 text-navy"></i>
                <div class="lh-sm d-none d-sm-block">
                    <div class="fw-semibold small">{{ auth()->user()->name }}</div>
                    <div class="text-muted" style="font-size:.72rem">Administrator</div>
                </div>
            </div>
        </div>

        @include('partials.alerts')

        <div class="admin-content">@yield('content')</div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script src="{{ asset('js/shop.js') }}"></script>
<script>
    (function () {
        const shell = document.getElementById('adminShell');
        const mobile = () => window.matchMedia('(max-width: 991.98px)').matches;
        if (!mobile() && localStorage.getItem('tb-sidebar') === 'collapsed') shell.classList.add('sidebar-collapsed');
        document.getElementById('sidebarToggle').addEventListener('click', function () {
            if (mobile()) { shell.classList.toggle('sidebar-open'); return; }
            const collapsed = shell.classList.toggle('sidebar-collapsed');
            localStorage.setItem('tb-sidebar', collapsed ? 'collapsed' : 'open');
        });
    })();
</script>
@stack('scripts')
</body>
</html>
