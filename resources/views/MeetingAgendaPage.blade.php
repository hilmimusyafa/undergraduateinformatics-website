@extends('layouts.homelayout')

@section('title', 'Agenda Pertemuan')
@section('description', 'Form agenda pertemuan dengan Program Studi Sarjana Informatika Telkom University.')

@section('content')
    <section class="tag-hero">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap mb-3">
                <a href="{{ route('home') }}" class="back-link"><i class="fa-solid fa-arrow-left"></i><img src="{{ asset('images/Logo2.png') }}" alt=""> <span>Kembali</span></a>
            </div>

            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <p class="eyebrow">FORM AGENDA PERTEMUAN</p>
                    <h1>FORM AGENDA PERTEMUAN DENGAN PRODI</h1>
                    <p class="lead">FORM OF MEETING AGENDA WITH PRODI</p>
                </div>
            </div>
        </div>
    </section>

    <section class="home-content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-xl-8">
                    <div class="topic-block">
                        @include('partials.Alerts')

                        <form method="POST" action="{{ route('meeting.agenda.store') }}" class="row g-3">
                            @csrf

                            <div class="col-md-6">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <input id="name" name="name" type="text" class="form-control" value="{{ old('name') }}" required>
                            </div>

                            <div class="col-md-6">
                                <label for="organization" class="form-label">Instansi / Perusahaan / Organisasi</label>
                                <input id="organization" name="organization" type="text" class="form-control" value="{{ old('organization') }}" required>
                            </div>

                            <div class="col-md-6">
                                <label for="position" class="form-label">Peran / Posisi</label>
                                <input id="position" name="position" type="text" class="form-control" value="{{ old('position') }}" required>
                            </div>

                            <div class="col-md-6">
                                <label for="phone" class="form-label">Nomor Telepon / WhatsApp</label>
                                <input id="phone" name="phone" type="text" class="form-control" value="{{ old('phone') }}" required>
                            </div>

                            <div class="col-12">
                                <label for="agenda" class="form-label">Tujuan / Agenda Pertemuan</label>
                                <textarea id="agenda" name="agenda" class="form-control" rows="5" placeholder="Tuliskan topik, tujuan, dan kebutuhan pertemuan dengan Program Studi ..." required>{{ old('agenda') }}</textarea>
                            </div>

                            <div class="col-12 d-flex justify-content-end gap-2 mt-3">
                                <button type="submit" class="modern-button modern-button--primary"><i class="fa-solid fa-paper-plane"></i> Kirim Agenda</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection