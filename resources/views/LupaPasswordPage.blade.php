@extends('layouts.authlayout')

@section('title', 'Lupa Password')

@section('content')
<div class="auth-header">
    <a href="{{ route('home') }}">
        <img class="auth-logo" src="/images/Logo2.png" alt="Logo S1 Informatika Telkom University" onerror="this.onerror=null; this.src='/images/Logo.png';">
    </a>
    <h2 class="auth-title">Pemulihan Password</h2>
    <p class="auth-subtitle">Masukkan email terdaftar untuk verifikasi akun</p>
</div>

<form method="POST" action="{{ route('submitEmailRecovery') }}" class="auth-form">
    @csrf

    <div class="form-group">
        <label for="email" class="auth-label">Email Terdaftar</label>
        <div class="auth-input-group">
            <i class="fa-regular fa-envelope auth-input-icon"></i>
            <input type="email" class="auth-input" id="email" name="email" value="{{ old('email') }}" placeholder="nama@telkomuniversity.ac.id" required autofocus>
        </div>
    </div>

    <button type="submit" class="btn-auth-submit mt-4">
        <span>Lanjutkan ke Pertanyaan Keamanan</span>
        <i class="fa-solid fa-arrow-right"></i>
    </button>
</form>

<a href="{{ Auth::check() ? route('posts.index') : route('login') }}" class="auth-back-link">
    <i class="fa-solid fa-arrow-left"></i>
    <span>Kembali ke Halaman Login</span>
</a>
@endsection
