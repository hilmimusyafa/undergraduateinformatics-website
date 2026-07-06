@extends('layouts.authlayout')

@section('title', 'Ganti Password')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="text-center">Lupa Password</h3>
        </div>
        <div class="card-body">
            <div class="back">
                <a href="{{ Auth::check() ? route('posts.index') : route('login') }}"><i
                        class="fa-solid fa-arrow-left fa-lg"></i>Kembali</a>
            </div>
            <form method="POST" action="{{ route('submitEmailRecovery') }}">
                @csrf
                <div class="email-field">
                    <label for="email" class="form-label">Masukkan Email yang Terdaftar</label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="Enter your email"
                        required>
                </div>
                <div class="bawah d-flex justify-content-end">
                    <button type="submit" class="btn btn-danger">Submit</button>
                </div>
            </form>
        </div>
    </div>
@endsection
