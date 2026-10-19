@extends('layouts.admin')

@section('title', 'Pesanan ' . $order->order_number)

@section('content')
@php
    $next = [
        'pending' => ['paid' => ['Konfirmasi Pembayaran', 'success', 'bi-check-circle']],
        'paid' => ['processing' => ['Proses Pesanan', 'primary', 'bi-box-seam']],
        'processing' => ['shipped' => ['Tandai Dikirim', 'secondary', 'bi-truck']],
        'shipped' => ['completed' => ['Tandai Selesai', 'success', 'bi-check2-all']],
    ][$order->status] ?? [];
@endphp

<a href="{{ route('admin.orders.index') }}" class="small"><i class="bi bi-arrow-left"></i> Kembali ke daftar pesanan</a>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 my-3">
    <div>
        <h2 class="h4 fw-bold text-navy mb-0">{{ $order->order_number }}</h2>
        <small class="text-muted">{{ $order->created_at->translatedFormat('d F Y, H:i') }}</small>
    </div>
    <span class="badge text-bg-{{ $order->status_color }} fs-6">{{ $order->status_label }}</span>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card-soft mb-4">
            <div class="p-3 border-bottom fw-bold text-navy">Produk Dipesan</div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead><tr><th>Produk</th><th class="text-end">Harga</th><th class="text-center">Qty</th><th class="text-end">Subtotal</th></tr></thead>
                    <tbody>
                    @foreach ($order->items as $item)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ $item->product?->image_url ?? asset('images/default-product.svg') }}" class="thumb-sm" alt="">
                                    <div>{{ $item->product_name }}@unless ($item->product)<div class="small text-danger">Produk sudah dihapus</div>@endunless</div>
                                </div>
                            </td>
                            <td class="text-end">@rupiah($item->price) / {{ $item->unit }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-end fw-semibold">@rupiah($item->subtotal)</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot><tr><th colspan="3" class="text-end">Total</th><th class="text-end price fs-5">@rupiah($order->total_amount)</th></tr></tfoot>
                </table>
            </div>
        </div>

        <div class="card-soft p-4">
            <h6 class="fw-bold text-navy mb-3">Bukti Pembayaran</h6>
            @if ($order->payment_proof)
                <a href="#" data-bs-toggle="modal" data-bs-target="#proofModal"><img src="{{ $order->payment_proof_url }}" alt="Bukti pembayaran" class="img-thumbnail" style="max-height:200px"></a>
                <div class="small text-muted mt-2">Klik gambar untuk memperbesar.</div>
            @else
                <div class="text-muted"><i class="bi bi-hourglass me-1"></i>Pelanggan belum mengunggah bukti pembayaran.</div>
            @endif
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card-soft p-4 mb-3">
            <h6 class="fw-bold text-navy mb-3">Update Status</h6>
            @if ($order->status === 'cancelled')
                <div class="alert alert-secondary mb-0 small">Pesanan dibatalkan dan tidak dapat diubah lagi. Stok sudah dikembalikan.</div>
            @else
                <div class="d-grid gap-2 mb-3">
                    @foreach ($next as $status => [$label, $color, $icon])
                        <form method="post" action="{{ route('admin.orders.status', $order) }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="{{ $status }}">
                            <button class="btn btn-{{ $color }} w-100"><i class="bi {{ $icon }} me-1"></i>{{ $label }}</button>
                        </form>
                    @endforeach
                </div>

                <form method="post" action="{{ route('admin.orders.status', $order) }}" class="mb-2">
                    @csrf @method('PATCH')
                    <label class="form-label small text-muted">Atau ubah manual</label>
                    <div class="input-group">
                        <select name="status" class="form-select">
                            @foreach (\App\Models\Order::STATUSES as $key => [$label])
                                <option value="{{ $key }}" @selected($order->status === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-outline-primary">Simpan</button>
                    </div>
                </form>

                <form method="post" action="{{ route('admin.orders.status', $order) }}" data-confirm="Batalkan pesanan ini? Stok akan dikembalikan.">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="cancelled">
                    <button class="btn btn-link text-danger px-0">Batalkan pesanan</button>
                </form>
            @endif
        </div>

        <div class="card-soft p-4">
            <h6 class="fw-bold text-navy mb-3">Pelanggan &amp; Pengiriman</h6>
            <div class="fw-semibold">{{ $order->recipient_name }}</div>
            <div class="small text-muted">{{ $order->user->email }}</div>
            <div class="small mb-2"><a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/\D/', '', $order->phone)) }}" target="_blank" rel="noopener"><i class="bi bi-whatsapp text-success me-1"></i>{{ $order->phone }}</a></div>
            <div class="small">{{ $order->shipping_address }}</div>
            @if ($order->notes)<div class="small text-muted mt-2">Catatan: {{ $order->notes }}</div>@endif
        </div>
    </div>
</div>

@if ($order->payment_proof)
    <div class="modal fade" id="proofModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header"><h6 class="modal-title">Bukti Pembayaran — {{ $order->order_number }}</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body text-center"><img src="{{ $order->payment_proof_url }}" alt="Bukti pembayaran" class="img-fluid"></div>
            </div>
        </div>
    </div>
@endif
@endsection
