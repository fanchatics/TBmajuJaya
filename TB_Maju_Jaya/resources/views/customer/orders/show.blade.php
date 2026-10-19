@extends('layouts.app')

@section('title', 'Pesanan ' . $order->order_number)

@section('content')
@php
    $flow = [
        ['pending', 'Dipesan', 'bi-bag'],
        ['paid', 'Dibayar', 'bi-credit-card'],
        ['processing', 'Diproses', 'bi-box-seam'],
        ['shipped', 'Dikirim', 'bi-truck'],
        ['completed', 'Selesai', 'bi-check2-circle'],
    ];
    $currentIndex = collect($flow)->search(fn ($s) => $s[0] === $order->status);
@endphp
<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">Pesanan Saya</a></li>
            <li class="breadcrumb-item active">{{ $order->order_number }}</li>
        </ol>
    </nav>

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h1 class="h4 fw-bold text-navy mb-0">{{ $order->order_number }}</h1>
            <small class="text-muted">Dibuat {{ $order->created_at->translatedFormat('d F Y, H:i') }}</small>
        </div>
        <div class="d-flex gap-2 align-items-center">
            <span class="badge text-bg-{{ $order->status_color }} fs-6">{{ $order->status_label }}</span>
            <a href="{{ route('orders.invoice', $order) }}" target="_blank" class="btn btn-outline-primary btn-sm"><i class="bi bi-printer me-1"></i>Cetak Invoice</a>
        </div>
    </div>

    @if ($order->status !== 'cancelled')
        <div class="card-soft p-4 mb-4">
            <ul class="timeline">
                @foreach ($flow as $i => [$key, $label, $icon])
                    <li class="{{ $currentIndex !== false && $i <= $currentIndex ? 'reached' : '' }}">
                        <div class="dot"><i class="bi {{ $icon }}"></i></div>{{ $label }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-8">
            @if ($order->status === 'pending')
                <div class="card-soft p-4 mb-4 border-warning" id="bayar">
                    <h6 class="fw-bold text-navy"><i class="bi bi-wallet2 me-1 text-amber"></i>Instruksi Pembayaran</h6>
                    <p class="mb-2">Transfer tepat sebesar <span class="price fs-5">@rupiah($order->total_amount)</span> ke salah satu rekening:</p>
                    @foreach ($bankAccounts as $bank)
                        <div class="d-flex justify-content-between align-items-center border rounded p-2 px-3 mb-2">
                            <div><span class="badge bg-navy me-2">{{ $bank['bank'] }}</span><strong>{{ $bank['number'] }}</strong></div>
                            <small class="text-muted">a.n. {{ $bank['holder'] }}</small>
                        </div>
                    @endforeach

                    <hr>
                    <h6 class="fw-semibold">Unggah Bukti Pembayaran</h6>
                    @if ($order->payment_proof)
                        <div class="alert alert-info py-2 small"><i class="bi bi-hourglass-split me-1"></i>Bukti sudah terkirim dan menunggu verifikasi admin. Anda dapat menggantinya jika salah.</div>
                        <a href="{{ $order->payment_proof_url }}" target="_blank"><img src="{{ $order->payment_proof_url }}" alt="Bukti pembayaran" class="img-thumbnail mb-3" style="max-height:180px"></a>
                    @endif
                    <form method="post" action="{{ route('orders.proof', $order) }}" enctype="multipart/form-data" class="row g-2">
                        @csrf
                        <div class="col-sm-8">
                            <input type="file" name="payment_proof" accept="image/*" class="form-control @error('payment_proof') is-invalid @enderror" required>
                            @error('payment_proof') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="form-text">JPG, PNG atau WEBP, maksimal 3 MB.</div>
                        </div>
                        <div class="col-sm-4"><button class="btn btn-accent w-100"><i class="bi bi-upload me-1"></i>Kirim Bukti</button></div>
                    </form>
                </div>
            @elseif ($order->payment_proof)
                <div class="card-soft p-3 mb-4">
                    <h6 class="fw-semibold">Bukti Pembayaran</h6>
                    <a href="{{ $order->payment_proof_url }}" target="_blank"><img src="{{ $order->payment_proof_url }}" alt="Bukti pembayaran" class="img-thumbnail" style="max-height:160px"></a>
                </div>
            @endif

            <div class="card-soft">
                <div class="p-3 border-bottom fw-bold text-navy">Rincian Produk</div>
                @foreach ($order->items as $item)
                    <div class="d-flex gap-3 p-3 {{ $loop->last ? '' : 'border-bottom' }}">
                        <img src="{{ $item->product?->image_url ?? asset('images/default-product.svg') }}" alt="" class="thumb-sm" style="width:60px;height:60px">
                        <div class="flex-grow-1">
                            <div class="fw-semibold">{{ $item->product_name }}</div>
                            <small class="text-muted">@rupiah($item->price) / {{ $item->unit }} &times; {{ $item->quantity }}</small>
                        </div>
                        <div class="fw-bold text-nowrap">@rupiah($item->subtotal)</div>
                    </div>
                @endforeach
                <div class="p-3 bg-slate d-flex justify-content-between rounded-bottom">
                    <span class="fw-bold">Total</span><span class="price fs-5">@rupiah($order->total_amount)</span>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card-soft p-4 mb-3">
                <h6 class="fw-bold text-navy mb-3"><i class="bi bi-geo-alt me-1 text-amber"></i>Pengiriman</h6>
                <div class="fw-semibold">{{ $order->recipient_name }}</div>
                <div class="small text-muted mb-2">{{ $order->phone }}</div>
                <div class="small">{{ $order->shipping_address }}</div>
                @if ($order->notes)<div class="small text-muted mt-2">Catatan: {{ $order->notes }}</div>@endif
            </div>

            @if ($order->canBeCancelled())
                <form method="post" action="{{ route('orders.cancel', $order) }}" data-confirm="Batalkan pesanan ini?">
                    @csrf
                    <button class="btn btn-outline-danger w-100"><i class="bi bi-x-circle me-1"></i>Batalkan Pesanan</button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
