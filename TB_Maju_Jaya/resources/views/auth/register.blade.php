@extends('layouts.app')

@section('title', 'Daftar')

@section('content')
<div class="container auth-wrap">
    <div class="card-soft p-4 p-md-5 w-100" style="max-width:480px">
        <div class="text-center mb-4">
            <span class="brand-mark mx-auto mb-3" style="width:56px;height:56px;font-size:1.8rem"><i class="bi bi-person-plus"></i></span>
            <h1 class="h4 fw-bold text-navy mb-1">Buat Akun Baru</h1>
            <p class="text-muted small mb-0">Belanja material bangunan jadi lebih mudah</p>
        </div>

        <form method="post" action="{{ route('register.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label" for="name">Nama Lengkap</label>
                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required autofocus>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label" for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autocomplete="username">
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label" for="phone">Nomor Telepon <span class="text-muted small">(opsional)</span></label>
                <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx">
                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="row g-3 mb-4">
                <div class="col-sm-6">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="new-password">
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-sm-6">
                    <label class="form-label" for="password_confirmation">Ulangi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required autocomplete="new-password">
                </div>
                <div class="col-12 form-text mt-1">Minimal 8 karakter.</div>
            </div>
            <button class="btn btn-accent btn-lg w-100">Daftar</button>
        </form>

        <p class="text-center small mt-4 mb-0">Sudah punya akun? <a href="{{ route('login') }}" class="fw-semibold">Masuk</a></p>
    </div>
</div>
@endsection
