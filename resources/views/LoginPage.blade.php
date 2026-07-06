@extends('layouts.authlayout')

@section('title', 'Admin Login')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="text-center">Admin Login</h3>
        </div>
        <div class="card-body">
            <div class="back">
                <a href="{{ route('home') }}"><i class="fa-solid fa-arrow-left fa-lg"></i>Kembali</a>
            </div>
            <form method="POST" action="{{ route('loginAttempt') }}">
                @csrf
                <div class="email-field">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email"
                        required>
                </div>
                <div class="password-field">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password"
                        placeholder="Enter your password" required autocomplete="off">
                </div>
                <div class="bawah d-flex justify-content-between">
                    <div class="lupa-password">
                        <a href="{{ route('forgotPassword') }}" class="text-decoration-none">Lupa Password?</a>
                    </div>
                    <button type="submit" class="btn btn-danger">Login</button>
                </div>
            </form>
        </div>
    </div>
@endsection
