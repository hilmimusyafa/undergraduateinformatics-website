@extends('layouts.authlayout')

@section('title', 'Pertanyaan Keamanan')

@section('content')
<div class="auth-header">
    <a href="{{ route('home') }}">
        <img class="auth-logo" src="/images/Logo2.png" alt="Logo S1 Informatika Telkom University" onerror="this.onerror=null; this.src='/images/Logo.png';">
    </a>
    <h2 class="auth-title">Pertanyaan Keamanan</h2>
    <p class="auth-subtitle">Jawab pertanyaan pemulihan untuk memverifikasi identitas Anda</p>
</div>

<form method="POST" action="{{ route('submitAnswerRecovery') }}" class="auth-form">
    @csrf

    <div class="form-group">
        <label for="pertanyaan1" class="auth-label text-dark fw-bold">
            <i class="fa-solid fa-circle-question text-danger me-1"></i>
            {{ $first_question }}
        </label>
        <div class="auth-input-group">
            <i class="fa-solid fa-key auth-input-icon"></i>
            <input type="text" class="auth-input" name="first_answer" id="pertanyaan1" placeholder="Masukkan jawaban pertama" required autocomplete="off">
        </div>
    </div>

    <div class="form-group">
        <label for="pertanyaan2" class="auth-label text-dark fw-bold">
            <i class="fa-solid fa-circle-question text-danger me-1"></i>
            {{ $second_question }}
        </label>
        <div class="auth-input-group">
            <i class="fa-solid fa-key auth-input-icon"></i>
            <input type="text" class="auth-input" name="second_answer" id="pertanyaan2" placeholder="Masukkan jawaban kedua" required autocomplete="off">
        </div>
    </div>

    <input type="hidden" name="user_id" value="{{ $user_id }}">

    <button type="submit" class="btn-auth-submit mt-4">
        <span>Verifikasi Jawaban</span>
        <i class="fa-solid fa-shield-check"></i>
    </button>
</form>

<a href="{{ route('forgotPassword') }}" class="auth-back-link">
    <i class="fa-solid fa-arrow-left"></i>
    <span>Kembali ke Langkah Sebelumnya</span>
</a>
@endsection
