@extends('layouts.app')

@section('title', 'Pesanan Saya')

@section('content')
<div class="container mt-4">
    <h1 class="h4 fw-bold text-navy mb-3">Pesanan Saya</h1>

    <ul class="nav nav-pills nav-pills-tb tabs-scroll mb-3">
        @foreach ($tabs as $key => [$label])
            <li class="nav-item">
                <a class="nav-link {{ $tab === $key ? 'active' : '' }}" href="{{ route('orders.index', ['tab' => $key]) }}">{{ $label }}</a>
            </li>
        @endforeach
    </ul>

    @forelse ($orders as $order)
        <div class="card-soft p-3 mb-3">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div>
                    <a href="{{ route('orders.show', $order) }}" class="fw-bold text-navy">{{ $order->order_number }}</a>
                    <div class="small text-muted">{{ $order->created_at->translatedFormat('d F Y, H:i') }} &middot; {{ $order->items_count }} jenis produk</div>
                </div>
                <span class="badge text-bg-{{ $order->status_color }} fs-6">{{ $order->status_label }}</span>
            </div>
            <hr class="my-2">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div>
                    <small class="text-muted">Total</small>
                    <div class="price fs-5">@rupiah($order->total_amount)</div>
                </div>
                <div class="d-flex gap-2">
                    @if ($order->status === 'pending' && ! $order->payment_proof)
                        <a href="{{ route('orders.show', $order) }}#bayar" class="btn btn-accent btn-sm">Bayar Sekarang</a>
                    @endif
                    <a href="{{ route('orders.invoice', $order) }}" target="_blank" class="btn btn-outline-primary btn-sm"><i class="bi bi-printer me-1"></i>Invoice</a>
                    <a href="{{ route('orders.show', $order) }}" class="btn btn-primary btn-sm">Detail</a>
                </div>
            </div>
        </div>
    @empty
        <div class="card-soft p-5 text-center">
            <i class="bi bi-receipt display-3 text-muted"></i>
            <h5 class="mt-3">Belum ada pesanan</h5>
            <a href="{{ route('products.index') }}" class="btn btn-accent mt-2">Mulai Belanja</a>
        </div>
    @endforelse

    <div class="d-flex justify-content-center">{{ $orders->links() }}</div>
</div>
@endsection
