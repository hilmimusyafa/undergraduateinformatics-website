@extends('layouts.authlayout')

@section('title', 'Login Admin')

@section('content')
<div class="auth-header">
    <a href="{{ route('home') }}">
        <img class="auth-logo" src="/images/Logo2.png" alt="Logo S1 Informatika Telkom University" onerror="this.onerror=null; this.src='/images/Logo.png';">
    </a>
    <h2 class="auth-title">Login Administrator</h2>
    <p class="auth-subtitle">Masuk untuk mengelola konten dan portal informasi</p>
</div>

<form method="POST" action="{{ route('loginAttempt') }}" class="auth-form">
    @csrf

    <div class="form-group">
        <label for="email" class="auth-label">Email Administrator</label>
        <div class="auth-input-group">
            <i class="fa-regular fa-envelope auth-input-icon"></i>
            <input type="email" class="auth-input" id="email" name="email" value="{{ old('email') }}" placeholder="nama@telkomuniversity.ac.id" required autofocus>
        </div>
    </div>

    <div class="form-group">
        <label for="password" class="auth-label">Password</label>
        <div class="auth-input-group">
            <i class="fa-solid fa-lock auth-input-icon"></i>
            <input type="password" class="auth-input" id="password" name="password" placeholder="Masukkan kata sandi" required autocomplete="current-password">
        </div>
    </div>

    <div class="auth-actions-row">
        <div class="form-check mb-0">
            <input class="form-check-input" type="checkbox" id="remember" name="remember">
            <label class="form-check-label text-muted small" for="remember">
                Ingat Saya
            </label>
        </div>
        <a href="{{ route('forgotPassword') }}" class="auth-forgot-link small">
            Lupa Password?
        </a>
    </div>

    <button type="submit" class="btn-auth-submit">
        <i class="fa-solid fa-right-to-bracket"></i>
        <span>Masuk ke Dashboard</span>
    </button>
</form>

<a href="{{ route('home') }}" class="auth-back-link">
    <i class="fa-solid fa-arrow-left"></i>
    <span>Kembali ke Halaman Utama</span>
</a>
@endsection
