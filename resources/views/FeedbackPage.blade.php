@extends('layouts.homelayout')

@section('title', 'Masukan & Saran')

@section('content')
<div class="py-5">
    <div class="container">
        <!-- Breadcrumb Navigation -->
        <nav class="breadcrumb-nav mb-4" aria-label="breadcrumb">
            <a href="{{ route('home') }}"><i class="fa-solid fa-house"></i> Beranda</a>
            <i class="fa-solid fa-chevron-right text-muted" style="font-size: 11px;"></i>
            <span>Masukan & Saran</span>
        </nav>

        <!-- Header Banner -->
        <div class="card border-0 rounded-4 shadow-sm bg-white p-4 p-md-5 mb-4">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="d-inline-flex align-items-center gap-2 bg-danger-subtle text-danger px-3 py-1 rounded-pill fw-bold small mb-3">
                        <i class="fa-solid fa-comment-dots"></i>
                        <span>Layanan Suara Mahasiswa & Sivitas</span>
                    </div>
                    <h1 class="display-6 fw-bold text-dark mb-3">Formulir Masukan & Saran Program Studi</h1>
                    <p class="text-muted fs-6 mb-0">
                        Kami sangat menghargai saran, keluhan, aspirasi, dan masukan konstruktif Anda demi peningkatan kualitas layanan akademik, fasilitas, dan kegiatan di lingkungan S1 Informatika Telkom University.
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                    @if(isset($feedbackLink) && $feedbackLink->link)
                        <a href="{{ $feedbackLink->link }}" target="_blank" rel="noopener noreferrer" class="btn btn-danger rounded-pill px-4 py-2 fw-semibold shadow-sm">
                            <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Buka di Tab Baru
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Feedback Form Container -->
        <div class="feedback-card">
            @if(isset($feedbackLink) && $feedbackLink->link)
                <div class="feedback-iframe-wrapper">
                    <iframe src="{{ $feedbackLink->link }}" frameborder="0" marginheight="0" marginwidth="0" allowfullscreen title="Formulir Feedback">
                        Memuat formulir...
                    </iframe>
                </div>
            @else
                <div class="p-5 text-center">
                    @include('partials.Empty')
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
