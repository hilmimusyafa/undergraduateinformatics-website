@extends('layouts.homelayout')

@section('title', 'Reservasi')
@section('description', 'Form reservasi pertemuan dengan Program Studi Sarjana Informatika.')

@section('content')
    <section class="tag-hero public-intro-hero">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap mb-3">
                <a href="{{ route('home') }}" class="back-link"><i class="fa-solid fa-arrow-left"></i><img src="{{ asset('images/Logo2.png') }}" alt=""> <span>Kembali</span></a>
            </div>

            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <p class="eyebrow">Layanan Mahasiswa</p>
                    <h1>Reservasi Pertemuan dengan Prodi</h1>
                    <p class="lead">Gunakan formulir ini untuk mengajukan jadwal pertemuan dengan Program Studi S1 Informatika terkait konsultasi, koordinasi, atau agenda akademik.</p>
                    <p class="hero-detail">Isi data diri, pilihan waktu, dan kebutuhan pertemuan dengan lengkap agar pengajuan dapat ditinjau oleh pengelola Program Studi.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="home-content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-xl-10">
                    <div class="topic-block">
                        @if ($reservationLink && $reservationLink->link)
                            <div class="responsive-iframe">
                                <iframe src="{{ $reservationLink->link }}" title="Form reservasi pertemuan dengan Program Studi" allowfullscreen></iframe>
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="fa-regular fa-calendar-check"></i>
                                <h3>Form reservasi belum tersedia</h3>
                                <p>Link formulir reservasi pertemuan belum dikonfigurasi oleh admin. Silakan gunakan form agenda pertemuan atau hubungi pengelola Program Studi.</p>
                                <a href="{{ route('meeting.agenda.show') }}" class="modern-button modern-button--primary">Ke Form Agenda Pertemuan</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection