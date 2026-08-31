@extends('layouts.authlayout')

@section('title', 'Buat Password Baru')

@section('content')
<div class="auth-header">
    <a href="{{ route('home') }}">
        <img class="auth-logo" src="/images/Logo2.png" alt="Logo S1 Informatika Telkom University" onerror="this.onerror=null; this.src='/images/Logo.png';">
    </a>
    <h2 class="auth-title">Buat Password Baru</h2>
    <p class="auth-subtitle">Masukkan kata sandi baru untuk akun administrator Anda</p>
</div>

<form method="POST" action="{{ route('submitPasswordRecovery') }}" class="auth-form">
    @csrf

    <div class="form-group">
        <label for="new_password" class="auth-label">Password Baru</label>
        <div class="auth-input-group">
            <i class="fa-solid fa-lock auth-input-icon"></i>
            <input type="password" class="auth-input" name="new_password" id="new_password" placeholder="Minimal 6 karakter" required minlength="6" autocomplete="new-password">
        </div>
    </div>

    <div class="form-group">
        <label for="confirm_new_password" class="auth-label">Konfirmasi Password Baru</label>
        <div class="auth-input-group">
            <i class="fa-solid fa-lock-open auth-input-icon"></i>
            <input type="password" class="auth-input" name="confirm_new_password" id="confirm_new_password" placeholder="Ulangi kata sandi baru" required minlength="6" autocomplete="new-password">
        </div>
    </div>

    <input type="hidden" name="user_id" value="{{ $user_id }}">

    <button type="submit" class="btn-auth-submit mt-4">
        <i class="fa-solid fa-check-circle"></i>
        <span>Simpan Password Baru</span>
    </button>
</form>

<a href="{{ route('forgotPassword') }}" class="auth-back-link">
    <i class="fa-solid fa-arrow-left"></i>
    <span>Batal & Kembali</span>
</a>
@endsection
