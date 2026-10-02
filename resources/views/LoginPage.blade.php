@extends('layouts.authlayout')

@section('title', 'Admin Login')

@section('content')
    <div class="auth-shell">
        <div class="auth-logo-wrap">
        </div>

        <div class="auth-card auth-card--login">
            <div class="auth-card__header">
                <img class="auth-logo" src="/images/logo.png" alt="Fakultas Informatika, School of Computing, Telkom University">
            </div>

            <div class="auth-card__body">
                <form method="POST" action="{{ route('admin.loginAttempt') }}">
                    @csrf
                    <div class="auth-field">
                        <label for="email" class="form-label">Username</label>
                        <div class="input-icon-wrap">
                            <i class="fa-solid fa-user"></i>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Username"
                                required>
                        </div>
                    </div>
                    <div class="auth-field">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-icon-wrap">
                            <i class="fa-solid fa-lock"></i>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Password" required autocomplete="off">
                            <button type="button" class="password-toggle" aria-label="Lihat password">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="auth-remember">
                        <label class="remember-check">
                            <input type="checkbox" name="remember">
                            <span>Ingat saya</span>
                        </label>
                    </div>
                    <div class="auth-actions">
                        <button type="submit" class="btn btn-danger">Masuk</button>
                    </div>
                </form>
                <div class="auth-back-action">
                    <a href="{{ route('home') }}" class="auth-back-link">
                        <i class="fa-solid fa-arrow-left"></i>
                        <span>Kembali ke Halaman Utama</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
