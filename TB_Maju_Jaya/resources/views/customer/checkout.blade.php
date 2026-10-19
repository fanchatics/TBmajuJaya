@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container mt-4">
    <div class="steps mb-4">
        <div class="step done"><span class="num"><i class="bi bi-check"></i></span>Keranjang</div><span class="line"></span>
        <div class="step current"><span class="num">2</span>Pengiriman & Pembayaran</div><span class="line"></span>
        <div class="step"><span class="num">3</span>Selesai</div>
    </div>

    <h1 class="h4 fw-bold text-navy mb-3">Checkout</h1>

    <form method="post" action="{{ route('checkout.store') }}">
        @csrf
        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card-soft p-4 mb-4">
                    <h6 class="fw-bold text-navy mb-3"><i class="bi bi-geo-alt me-1 text-amber"></i>Alamat Pengiriman</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="recipient_name">Nama Penerima</label>
                            <input type="text" id="recipient_name" name="recipient_name" class="form-control @error('recipient_name') is-invalid @enderror"
                                   value="{{ old('recipient_name', $user->name) }}" required>
                            @error('recipient_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="phone">Nomor Telepon / WhatsApp</label>
                            <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                   value="{{ old('phone', $user->phone) }}" placeholder="08xxxxxxxxxx" required>
                            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="shipping_address">Alamat Lengkap</label>
                            <textarea id="shipping_address" name="shipping_address" rows="3" class="form-control @error('shipping_address') is-invalid @enderror"
                                      placeholder="Jalan, nomor rumah, RT/RW, kelurahan, patokan..." required>{{ old('shipping_address', $user->address) }}</textarea>
                            @error('shipping_address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="form-text">Pengiriman hanya untuk radius {{ config('shop.delivery_radius_km') }} km dari toko.</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="notes">Catatan (opsional)</label>
                            <input type="text" id="notes" name="notes" class="form-control" value="{{ old('notes') }}" placeholder="Contoh: titip di pos satpam">
                        </div>
                    </div>
                </div>

                <div class="card-soft p-4">
                    <h6 class="fw-bold text-navy mb-3"><i class="bi bi-bank me-1 text-amber"></i>Pembayaran — Transfer Bank</h6>
                    <p class="text-muted small">Setelah pesanan dibuat, transfer sesuai total ke salah satu rekening di bawah, lalu unggah bukti pembayaran di halaman pesanan.</p>
                    @foreach ($bankAccounts as $bank)
                        <div class="d-flex justify-content-between align-items-center border rounded p-3 mb-2">
                            <div><span class="badge bg-navy me-2">{{ $bank['bank'] }}</span><span class="fw-bold fs-5">{{ $bank['number'] }}</span></div>
                            <small class="text-muted">a.n. {{ $bank['holder'] }}</small>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card-soft p-4 position-sticky" style="top:100px">
                    <h6 class="fw-bold text-navy mb-3">Ringkasan Pesanan</h6>
                    @foreach ($carts as $cart)
                        <div class="d-flex justify-content-between small mb-2">
                            <span class="pe-2">{{ $cart->product->name }} <span class="text-muted">&times; {{ $cart->quantity }}</span></span>
                            <span class="fw-semibold text-nowrap">@rupiah($cart->subtotal)</span>
                        </div>
                    @endforeach
                    <hr>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-semibold">Total Bayar</span><span class="price fs-4">@rupiah($total)</span>
                    </div>
                    <button type="submit" class="btn btn-accent btn-lg w-100"><i class="bi bi-bag-check me-1"></i>Buat Pesanan</button>
                    <a href="{{ route('cart.index') }}" class="btn btn-link w-100 mt-1">Kembali ke keranjang</a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
