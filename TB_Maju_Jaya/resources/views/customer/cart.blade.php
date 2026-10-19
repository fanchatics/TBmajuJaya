@extends('layouts.app')

@section('title', 'Keranjang')

@section('content')
<div class="container mt-4">
    <div class="steps mb-4">
        <div class="step current"><span class="num">1</span>Keranjang</div><span class="line"></span>
        <div class="step"><span class="num">2</span>Pengiriman & Pembayaran</div><span class="line"></span>
        <div class="step"><span class="num">3</span>Selesai</div>
    </div>

    <h1 class="h4 fw-bold text-navy mb-3">Keranjang Belanja</h1>

    @if ($carts->isEmpty())
        <div class="card-soft p-5 text-center">
            <i class="bi bi-cart-x display-3 text-muted"></i>
            <h5 class="mt-3">Keranjang Anda masih kosong</h5>
            <p class="text-muted">Yuk, temukan kebutuhan bangunan Anda.</p>
            <a href="{{ route('products.index') }}" class="btn btn-accent">Mulai Belanja</a>
        </div>
    @else
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card-soft">
                    @foreach ($carts as $cart)
                        <div class="d-flex gap-3 p-3 {{ $loop->last ? '' : 'border-bottom' }}">
                            <a href="{{ route('products.show', $cart->product) }}">
                                <img src="{{ $cart->product->image_url }}" alt="" class="thumb-sm" style="width:84px;height:84px">
                            </a>
                            <div class="flex-grow-1">
                                <a href="{{ route('products.show', $cart->product) }}" class="fw-semibold text-navy">{{ $cart->product->name }}</a>
                                <div class="small text-muted">{{ $cart->product->category->name }}</div>
                                <div class="price mt-1">@rupiah($cart->product->price) <small>/ {{ $cart->product->unit }}</small></div>
                                @if (! $cart->product->in_stock)
                                    <span class="badge bg-danger mt-1">Stok habis</span>
                                @elseif ($cart->quantity > $cart->product->stock)
                                    <span class="badge bg-warning text-dark mt-1">Stok tersisa {{ $cart->product->stock }}</span>
                                @endif

                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mt-2">
                                    <form method="post" action="{{ route('cart.update', $cart->id) }}">
                                        @csrf @method('PATCH')
                                        <div class="qty-picker">
                                            <button type="button" data-qty-step="-1">&minus;</button>
                                            <input type="number" name="quantity" value="{{ $cart->quantity }}" min="1" max="{{ max($cart->product->stock, 1) }}" data-autosubmit>
                                            <button type="button" data-qty-step="1">+</button>
                                        </div>
                                    </form>
                                    <div class="fw-bold">@rupiah($cart->subtotal)</div>
                                    <form method="post" action="{{ route('cart.destroy', $cart->id) }}">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" aria-label="Hapus"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-between mt-3">
                    <a href="{{ route('products.index') }}" class="btn btn-outline-primary"><i class="bi bi-arrow-left me-1"></i>Lanjut Belanja</a>
                    <form method="post" action="{{ route('cart.clear') }}" data-confirm="Kosongkan seluruh keranjang?">
                        @csrf @method('DELETE')
                        <button class="btn btn-link text-danger">Kosongkan Keranjang</button>
                    </form>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card-soft p-4 position-sticky" style="top:100px">
                    <h6 class="fw-bold text-navy mb-3">Ringkasan Belanja</h6>
                    <div class="d-flex justify-content-between mb-2"><span class="text-muted">Total item</span><span>{{ $carts->sum('quantity') }}</span></div>
                    <div class="d-flex justify-content-between mb-2"><span class="text-muted">Ongkos kirim</span><span class="small">Dikonfirmasi admin</span></div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-semibold">Total</span><span class="price fs-4">@rupiah($total)</span>
                    </div>
                    <a href="{{ route('checkout.create') }}" class="btn btn-accent btn-lg w-100">Lanjut ke Checkout <i class="bi bi-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
