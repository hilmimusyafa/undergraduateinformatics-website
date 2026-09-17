@extends('layouts.homelayout')

@section('title', 'Feedback')

@section('description', 'Form masukan dan saran untuk Program Studi Sarjana Informatika.')

@section('content')
    <section class="tag-hero">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap mb-3">
                <a href="{{ route('home') }}" class="back-link"><i class="fa-solid fa-arrow-left"></i><img src="{{ asset('images/Logo2.png') }}" alt=""> <span>Kembali</span></a>
            </div>

            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <p class="eyebrow">Masukan & Saran</p>
                    <h1>Feedback Program Studi</h1>
                    <p class="lead">Sampaikan masukan, kritik, atau saran Anda agar layanan dan pembelajaran di Program Studi S1 Informatika semakin baik.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="home-content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-xl-10">
                    <div class="topic-block">
                        @if ($feedbackLink && $feedbackLink->link)
                            <div class="responsive-iframe">
                                <iframe src="{{ $feedbackLink->link }}" title="Feedback form" allowfullscreen></iframe>
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="fa-regular fa-comment-dots"></i>
                                <h3>Form feedback belum tersedia</h3>
                                <p>Link formulir feedback belum dikonfigurasi oleh admin. Silakan hubungi administrator untuk mengaktifkan form ini.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
