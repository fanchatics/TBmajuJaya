@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="row g-3 mb-4">
    @foreach ([
        ['Total Penjualan', 'Rp ' . number_format($stats['sales'], 0, ',', '.'), 'bi-cash-coin', 'bg-success-subtle text-success'],
        ['Total Pesanan', $stats['orders'] . ' (' . $stats['pending'] . ' menunggu bayar)', 'bi-receipt-cutoff', 'bg-primary-subtle text-primary'],
        ['Total Produk', $stats['products'], 'bi-box-seam', 'bg-warning-subtle text-warning-emphasis'],
        ['Total Customer', $stats['customers'], 'bi-people', 'bg-info-subtle text-info-emphasis'],
    ] as [$label, $value, $icon, $color])
        <div class="col-sm-6 col-xl-3">
            <div class="stat-card">
                <div class="ico {{ $color }}"><i class="bi {{ $icon }}"></i></div>
                <div><div class="label">{{ $label }}</div><div class="value" style="{{ strlen((string) $value) > 14 ? 'font-size:1.05rem' : '' }}">{{ $value }}</div></div>
            </div>
        </div>
    @endforeach
</div>

<div class="row g-4">
    <div class="col-xl-8">
        <div class="card-soft">
            <div class="p-3 d-flex justify-content-between align-items-center border-bottom">
                <h6 class="fw-bold text-navy mb-0">Pesanan Terbaru</h6>
                <a href="{{ route('admin.orders.index') }}" class="small fw-semibold">Lihat semua</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead><tr><th>No. Pesanan</th><th>Pelanggan</th><th>Total</th><th>Status</th><th></th></tr></thead>
                    <tbody>
                    @forelse ($recentOrders as $order)
                        <tr>
                            <td><a href="{{ route('admin.orders.show', $order) }}" class="fw-semibold">{{ $order->order_number }}</a><div class="small text-muted">{{ $order->created_at->diffForHumans() }}</div></td>
                            <td>{{ $order->user->name }}</td>
                            <td class="fw-semibold">@rupiah($order->total_amount)</td>
                            <td><span class="badge text-bg-{{ $order->status_color }}">{{ $order->status_label }}</span></td>
                            <td class="text-end"><a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">Detail</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Belum ada pesanan.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card-soft">
            <div class="p-3 d-flex justify-content-between align-items-center border-bottom">
                <h6 class="fw-bold text-navy mb-0"><i class="bi bi-exclamation-triangle-fill text-warning me-1"></i>Stok Menipis</h6>
                <a href="{{ route('admin.products.index', ['stock' => 'low']) }}" class="small fw-semibold">Lihat semua</a>
            </div>
            <ul class="list-group list-group-flush">
                @forelse ($lowStockProducts as $product)
                    <li class="list-group-item d-flex justify-content-between align-items-center gap-2">
                        <div class="d-flex align-items-center gap-2 min-w-0">
                            <img src="{{ $product->image_url }}" class="thumb-sm" alt="">
                            <div class="lh-sm"><a href="{{ route('admin.products.edit', $product) }}" class="fw-semibold small">{{ Str::limit($product->name, 28) }}</a><div class="text-muted" style="font-size:.75rem">{{ $product->category->name }}</div></div>
                        </div>
                        <span class="badge {{ $product->stock === 0 ? 'bg-danger' : 'bg-warning text-dark' }}">{{ $product->stock }} {{ $product->unit }}</span>
                    </li>
                @empty
                    <li class="list-group-item text-center text-muted py-4">Semua stok aman.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
