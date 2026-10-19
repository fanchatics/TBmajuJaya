@extends('layouts.admin')

@section('title', 'Pesanan Masuk')

@section('content')
<div class="card-soft">
    <div class="p-3 border-bottom">
        <div class="d-flex flex-wrap gap-2 mb-3">
            <a href="{{ route('admin.orders.index', array_filter(['q' => request('q')])) }}" class="btn btn-sm {{ $status ? 'btn-outline-primary' : 'btn-primary' }} rounded-pill">
                Semua <span class="badge bg-light text-dark ms-1">{{ $statusCounts->sum() }}</span>
            </a>
            @foreach (\App\Models\Order::STATUSES as $key => [$label, $color])
                <a href="{{ route('admin.orders.index', array_filter(['status' => $key, 'q' => request('q')])) }}"
                   class="btn btn-sm rounded-pill {{ $status === $key ? 'btn-' . $color : 'btn-outline-' . $color }}">
                    {{ $label }} <span class="badge bg-light text-dark ms-1">{{ $statusCounts[$key] ?? 0 }}</span>
                </a>
            @endforeach
        </div>
        <form method="get" class="d-flex gap-2">
            @if ($status) <input type="hidden" name="status" value="{{ $status }}"> @endif
            <input type="search" name="q" value="{{ request('q') }}" class="form-control" style="max-width:300px" placeholder="Cari no. pesanan / nama penerima...">
            <button class="btn btn-primary"><i class="bi bi-search"></i></button>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead><tr><th>No. Pesanan</th><th>Pelanggan</th><th>Tanggal</th><th>Total</th><th>Bukti Bayar</th><th>Status</th><th></th></tr></thead>
            <tbody>
            @forelse ($orders as $order)
                <tr>
                    <td><a href="{{ route('admin.orders.show', $order) }}" class="fw-semibold">{{ $order->order_number }}</a><div class="small text-muted">{{ $order->items_count }} jenis produk</div></td>
                    <td>{{ $order->recipient_name }}<div class="small text-muted">{{ $order->user->email }}</div></td>
                    <td class="small">{{ $order->created_at->translatedFormat('d M Y, H:i') }}</td>
                    <td class="fw-semibold">@rupiah($order->total_amount)</td>
                    <td>@if ($order->payment_proof)<span class="badge bg-success-subtle text-success-emphasis"><i class="bi bi-image me-1"></i>Ada</span>@else<span class="text-muted small">—</span>@endif</td>
                    <td><span class="badge text-bg-{{ $order->status_color }}">{{ $order->status_label }}</span></td>
                    <td class="text-end"><a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">Detail</a></td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted py-5">Tidak ada pesanan.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if ($orders->hasPages())
        <div class="p-3 border-top d-flex justify-content-center">{{ $orders->links() }}</div>
    @endif
</div>
@endsection
