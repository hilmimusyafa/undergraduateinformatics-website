@extends('layouts.authlayout')

@section('title', 'Admin Login')

@section('content')
    <div class="auth-card auth-card--login">
        <div class="auth-card__brand">
            <div class="auth-brand-mark"><i class="fa-solid fa-shield-halved"></i></div>
            <p>Dashboard Informasi</p>
            <h1>Admin S1 Informatika</h1>
        </div>

        <div class="auth-card__body">
            <form method="POST" action="{{ route('admin.loginAttempt') }}">
                @csrf
                <div class="auth-field">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-icon-wrap">
                        <i class="fa-solid fa-envelope"></i>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email"
                            required>
                    </div>
                </div>
                <div class="auth-field">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-icon-wrap">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" class="form-control" id="password" name="password"
                            placeholder="Masukkan password" required autocomplete="off">
                    </div>
                </div>
                <div class="auth-actions">
                    <div class="lupa-password">
                        <a href="{{ route('admin.forgotPassword') }}" class="text-decoration-none">Lupa Password?</a>
                    </div>
                    <button type="submit" class="btn btn-danger"><i class="fa-solid fa-right-to-bracket"></i> Login</button>
                </div>
            </form>
        </div>
    </div>
@endsection
