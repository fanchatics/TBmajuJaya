@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="container mt-4" style="max-width:760px">
    <h1 class="h4 fw-bold text-navy mb-3">Profil Saya</h1>

    <form method="post" action="{{ route('profile.update') }}" class="card-soft p-4">
        @csrf @method('PUT')
        <h6 class="fw-bold text-navy mb-3">Data Diri</h6>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label" for="name">Nama</label>
                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label" for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label" for="phone">Nomor Telepon</label>
                <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}">
                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-12">
                <label class="form-label" for="address">Alamat Utama</label>
                <textarea id="address" name="address" rows="2" class="form-control @error('address') is-invalid @enderror">{{ old('address', $user->address) }}</textarea>
                @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <hr class="my-4">
        <h6 class="fw-bold text-navy mb-1">Ganti Password</h6>
        <p class="text-muted small">Kosongkan jika tidak ingin mengubah password.</p>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label" for="current_password">Password Saat Ini</label>
                <input type="password" id="current_password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" autocomplete="current-password">
                @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
                <label class="form-label" for="password">Password Baru</label>
                <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password">
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
                <label class="form-label" for="password_confirmation">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" autocomplete="new-password">
            </div>
        </div>

        <div class="mt-4"><button class="btn btn-accent px-4">Simpan Perubahan</button></div>
    </form>
</div>
@endsection
