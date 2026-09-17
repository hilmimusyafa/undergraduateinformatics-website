@extends('layouts.homelayout')

@section('title', 'Reservasi')
@section('description', 'Halaman reservasi untuk keperluan pertemuan dan agenda dengan Program Studi Sarjana Informatika.')

@section('content')
    <section class="tag-hero">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap mb-3">
                <a href="{{ route('home') }}" class="back-link"><i class="fa-solid fa-arrow-left"></i><img src="{{ asset('images/Logo2.png') }}" alt=""> <span>Kembali</span></a>
            </div>

            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <p class="eyebrow">Reservasi</p>
                    <h1>Form Reservasi Pertemuan</h1>
                    <p class="lead">Silakan gunakan kanal reservasi ini untuk mengajukan jadwal pertemuan atau agenda dengan Program Studi S1 Informatika.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="home-content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-xl-10">
                    <div class="topic-block">
                        <div class="empty-state">
                            <i class="fa-regular fa-calendar-check"></i>
                            <h3>Reservasi belum aktif</h3>
                            <p>Form reservasi ini saat ini belum dikonfigurasi. Anda dapat menggunakan form agenda pertemuan untuk mengirimkan kebutuhan pertemuan langsung ke Prodi.</p>
                            <a href="{{ route('meeting.agenda.show') }}" class="modern-button modern-button--primary">Ke Form Agenda Pertemuan</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection