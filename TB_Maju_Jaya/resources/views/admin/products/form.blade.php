@extends('layouts.admin')

@php $editing = $product->exists; @endphp
@section('title', $editing ? 'Edit Produk' : 'Tambah Produk')

@section('content')
<form method="post" enctype="multipart/form-data"
      action="{{ $editing ? route('admin.products.update', $product) : route('admin.products.store') }}">
    @csrf
    @if ($editing) @method('PUT') @endif

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card-soft p-4">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold" for="name">Nama Produk</label>
                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" for="category_id">Kategori</label>
                        <select id="category_id" name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                            <option value="">Pilih kategori...</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected((int) old('category_id', $product->category_id) === $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" for="unit">Satuan</label>
                        <input type="text" id="unit" name="unit" list="units" class="form-control @error('unit') is-invalid @enderror" value="{{ old('unit', $product->unit) }}" required>
                        <datalist id="units">@foreach (['pcs','sak','batang','lembar','kaleng','dus','roll','set','kg','meter','liter'] as $unit)<option value="{{ $unit }}">@endforeach</datalist>
                        @error('unit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" for="price">Harga (Rp)</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" id="price" name="price" min="0" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $product->price) }}" required>
                            @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" for="stock">Stok</label>
                        <input type="number" id="stock" name="stock" min="0" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', $product->stock) }}" required>
                        @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold" for="description">Deskripsi</label>
                        <textarea id="description" name="description" rows="5" class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description) }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card-soft p-4">
                <label class="form-label fw-semibold">Gambar Produk</label>
                <div class="dropzone" id="dropzone" tabindex="0">
                    <div id="dzEmpty" class="{{ $editing && $product->image ? 'd-none' : '' }}">
                        <i class="bi bi-cloud-arrow-up fs-1 text-muted"></i>
                        <div class="fw-semibold">Tarik &amp; lepas gambar di sini</div>
                        <small class="text-muted">atau klik untuk memilih (JPG/PNG/WEBP, maks 2 MB)</small>
                    </div>
                    <img id="dzPreview" src="{{ $editing && $product->image ? $product->image_url : '' }}" alt="Preview" class="{{ $editing && $product->image ? '' : 'd-none' }}">
                </div>
                <input type="file" id="image" name="image" accept="image/*" class="d-none">
                @error('image') <div class="text-danger small mt-2">{{ $message }}</div> @enderror

                @if ($editing && $product->image)
                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" name="remove_image" value="1" id="remove_image">
                        <label class="form-check-label small" for="remove_image">Hapus gambar saat ini</label>
                    </div>
                @endif
                <small class="text-muted d-block mt-2">Tanpa gambar, produk memakai gambar bawaan.</small>
            </div>

            <div class="d-grid gap-2 mt-3">
                <button class="btn btn-accent btn-lg"><i class="bi bi-check-lg me-1"></i>{{ $editing ? 'Simpan Perubahan' : 'Simpan Produk' }}</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-light">Batal</a>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    (function () {
        const zone = document.getElementById('dropzone');
        const input = document.getElementById('image');
        const preview = document.getElementById('dzPreview');
        const empty = document.getElementById('dzEmpty');

        function show(file) {
            if (!file || !file.type.startsWith('image/')) return;
            preview.src = URL.createObjectURL(file);
            preview.classList.remove('d-none');
            empty.classList.add('d-none');
        }

        zone.addEventListener('click', () => input.click());
        zone.addEventListener('keydown', e => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); input.click(); } });
        input.addEventListener('change', () => show(input.files[0]));
        ['dragenter', 'dragover'].forEach(ev => zone.addEventListener(ev, e => { e.preventDefault(); zone.classList.add('dragover'); }));
        ['dragleave', 'drop'].forEach(ev => zone.addEventListener(ev, e => { e.preventDefault(); zone.classList.remove('dragover'); }));
        zone.addEventListener('drop', e => {
            if (e.dataTransfer.files.length) { input.files = e.dataTransfer.files; show(input.files[0]); }
        });
    })();
</script>
@endpush
