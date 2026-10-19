@extends('layouts.admin')

@section('title', 'Pelanggan')

@section('content')
<div class="card-soft">
    <div class="p-3 border-bottom">
        <form method="get" class="d-flex gap-2">
            <input type="search" name="q" value="{{ request('q') }}" class="form-control" style="max-width:300px" placeholder="Cari nama / email...">
            <button class="btn btn-primary"><i class="bi bi-search"></i></button>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead><tr><th>Nama</th><th>Kontak</th><th>Bergabung</th><th class="text-center">Pesanan</th><th class="text-end">Total Belanja</th></tr></thead>
            <tbody>
            @forelse ($customers as $customer)
                <tr>
                    <td class="fw-semibold">{{ $customer->name }}</td>
                    <td>{{ $customer->email }}<div class="small text-muted">{{ $customer->phone ?: '—' }}</div></td>
                    <td class="small">{{ $customer->created_at->translatedFormat('d M Y') }}</td>
                    <td class="text-center"><span class="badge bg-light text-dark border">{{ $customer->orders_count }}</span></td>
                    <td class="text-end fw-semibold">@rupiah($customer->spent ?? 0)</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted py-5">Belum ada pelanggan.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if ($customers->hasPages())<div class="p-3 border-top d-flex justify-content-center">{{ $customers->links() }}</div>@endif
</div>
@endsection
