@extends('layouts.authlayout')

@section('title', 'Admin Login')

@section('content')
    <div class="auth-card">
        <div class="auth-card__header">
            <img src="/images/Logo.png" alt="Bachelor of Informatics Telkom University">
            <p>PORTAL ADMINISTRATOR</p>
            <h1>Admin Login</h1>
        </div>
        <div class="auth-card__body">
            <div class="back">
                <a href="{{ route('home') }}"><i class="fa-solid fa-arrow-left"></i>Kembali ke website</a>
            </div>
            <form method="POST" action="{{ route('loginAttempt') }}">
                @csrf
                <div class="auth-field">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email"
                        required>
                </div>
                <div class="auth-field">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password"
                        placeholder="Masukkan password" required autocomplete="off">
                </div>
                <div class="auth-actions">
                    <div class="lupa-password">
                        <a href="{{ route('forgotPassword') }}" class="text-decoration-none">Lupa Password?</a>
                    </div>
                    <button type="submit" class="btn btn-danger"><i class="fa-solid fa-right-to-bracket"></i> Login</button>
                </div>
            </form>
        </div>
    </div>
@endsection
