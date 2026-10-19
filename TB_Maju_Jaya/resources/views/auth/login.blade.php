@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
<div class="container auth-wrap">
    <div class="card-soft p-4 p-md-5 w-100" style="max-width:440px">
        <div class="text-center mb-4">
            <span class="brand-mark mx-auto mb-3" style="width:56px;height:56px;font-size:1.8rem"><i class="bi bi-hammer"></i></span>
            <h1 class="h4 fw-bold text-navy mb-1">Masuk ke Akun</h1>
            <p class="text-muted small mb-0">Selamat datang kembali di TB Maju Jaya</p>
        </div>

        <form method="post" action="{{ route('login.attempt') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label" for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus autocomplete="username">
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label" for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required autocomplete="current-password">
            </div>
            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" value="1" @checked(old('remember'))>
                <label class="form-check-label small" for="remember">Ingat saya</label>
            </div>
            <button class="btn btn-accent btn-lg w-100">Masuk</button>
        </form>

        <p class="text-center small mt-4 mb-0">Belum punya akun? <a href="{{ route('register') }}" class="fw-semibold">Daftar sekarang</a></p>
    </div>
</div>
@endsection
