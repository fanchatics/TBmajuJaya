@extends('layouts.admin')

@section('title', 'Kategori')

@php
    $icons = ['bi-droplet-half' => 'Pipa', 'bi-bricks' => 'Semen', 'bi-palette-fill' => 'Cat', 'bi-tools' => 'Perkakas',
              'bi-lightning-charge-fill' => 'Listrik', 'bi-building' => 'Baja/Atap', 'bi-box-seam' => 'Umum', 'bi-hammer' => 'Palu',
              'bi-wrench-adjustable' => 'Kunci', 'bi-lightbulb-fill' => 'Lampu', 'bi-door-open' => 'Pintu', 'bi-tree-fill' => 'Kayu'];
@endphp

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <form method="post" action="{{ route('admin.categories.store') }}" class="card-soft p-4">
            @csrf
            <h6 class="fw-bold text-navy mb-3">Tambah Kategori</h6>
            <div class="mb-3">
                <label class="form-label" for="name">Nama</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label" for="icon">Ikon</label>
                <select id="icon" name="icon" class="form-select">
                    @foreach ($icons as $class => $label)<option value="{{ $class }}">{{ $label }} ({{ $class }})</option>@endforeach
                </select>
            </div>
            <button class="btn btn-accent w-100">Tambah</button>
        </form>
    </div>

    <div class="col-lg-8">
        <div class="card-soft">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead><tr><th>Kategori</th><th>Slug</th><th>Produk</th><th class="text-end">Aksi</th></tr></thead>
                    <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td><i class="bi {{ $category->icon }} me-2 text-amber fs-5"></i><span class="fw-semibold">{{ $category->name }}</span></td>
                            <td class="text-muted small">{{ $category->slug }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $category->products_count }}</span></td>
                            <td class="text-end text-nowrap">
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#edit{{ $category->id }}"><i class="bi bi-pencil"></i></button>
                                <form method="post" action="{{ route('admin.categories.destroy', $category) }}" class="d-inline" data-confirm="Hapus kategori &quot;{{ $category->name }}&quot;?">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-4">Belum ada kategori.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@foreach ($categories as $category)
    <div class="modal fade" id="edit{{ $category->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form method="post" action="{{ route('admin.categories.update', $category) }}" class="modal-content">
                @csrf @method('PUT')
                <div class="modal-header"><h6 class="modal-title fw-bold">Edit Kategori</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">Nama</label><input type="text" name="name" class="form-control" value="{{ $category->name }}" required></div>
                    <div>
                        <label class="form-label">Ikon</label>
                        <select name="icon" class="form-select">
                            @foreach ($icons + [$category->icon => $category->icon] as $class => $label)
                                <option value="{{ $class }}" @selected($category->icon === $class)>{{ $label }} ({{ $class }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button class="btn btn-accent">Simpan</button></div>
            </form>
        </div>
    </div>
@endforeach
@endsection
