@extends('layouts.admin')

@section('title', 'Produk')

@section('content')
<div class="card-soft">
    <div class="p-3 d-flex flex-wrap gap-2 justify-content-between align-items-center border-bottom">
        <form method="get" class="d-flex flex-wrap gap-2 flex-grow-1">
            <input type="search" name="q" value="{{ request('q') }}" class="form-control" style="max-width:260px" placeholder="Cari produk...">
            <select name="category" class="form-select" style="max-width:200px" data-autosubmit>
                <option value="">Semua kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected((string) request('category') === (string) $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
            <select name="stock" class="form-select" style="max-width:170px" data-autosubmit>
                <option value="">Semua stok</option>
                <option value="low" @selected(request('stock') === 'low')>Stok menipis</option>
            </select>
            <button class="btn btn-primary"><i class="bi bi-search"></i></button>
            @if (request()->hasAny(['q', 'category', 'stock']))
                <a href="{{ route('admin.products.index') }}" class="btn btn-light">Reset</a>
            @endif
        </form>
        <a href="{{ route('admin.products.create') }}" class="btn btn-accent"><i class="bi bi-plus-lg me-1"></i>Tambah Produk</a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead><tr><th>Produk</th><th>Kategori</th><th>Harga</th><th>Stok</th><th class="text-end">Aksi</th></tr></thead>
            <tbody>
            @forelse ($products as $product)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ $product->image_url }}" class="thumb-sm" alt="">
                            <div><div class="fw-semibold">{{ $product->name }}</div><small class="text-muted">/ {{ $product->unit }}</small></div>
                        </div>
                    </td>
                    <td>{{ $product->category->name }}</td>
                    <td class="fw-semibold">@rupiah($product->price)</td>
                    <td>
                        @if ($product->stock === 0) <span class="badge bg-danger">Habis</span>
                        @elseif ($product->is_low_stock) <span class="badge bg-warning text-dark">{{ $product->stock }} (menipis)</span>
                        @else <span class="badge bg-success-subtle text-success-emphasis">{{ $product->stock }}</span> @endif
                    </td>
                    <td class="text-end text-nowrap">
                        <a href="{{ route('products.show', $product) }}" target="_blank" class="btn btn-sm btn-light" title="Lihat di toko"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>
                        <form method="post" action="{{ route('admin.products.destroy', $product) }}" class="d-inline" data-confirm="Hapus produk &quot;{{ $product->name }}&quot;?">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted py-5">Tidak ada produk ditemukan.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if ($products->hasPages())
        <div class="p-3 d-flex justify-content-between align-items-center border-top">
            <small class="text-muted">{{ $products->firstItem() }}–{{ $products->lastItem() }} dari {{ $products->total() }} produk</small>
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection
