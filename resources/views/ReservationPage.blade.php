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
                    <p class="lead">Gunakan formulir ini untuk mengajukan jadwal pertemuan dengan Program Studi S1 Informatika terkait konsultasi, koordinasi, atau agenda akademik. Isi data diri, pilihan waktu, dan kebutuhan pertemuan dengan lengkap agar pengajuan dapat ditinjau oleh pengelola Program Studi.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="home-content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col">
                    {{-- 1. INFORMASI JADWAL & AGENDA PERTEMUAN (DITEMPATKAN DI ATAS FORM) --}}
                    <div class="topic-block mb-4" id="reservation-schedule-block">
                        <div class="topic-header">
                            <div class="topic-header__title-wrap">
                                <span class="topic-mark"></span>
                                <h2>Informasi Jadwal & Agenda Pertemuan</h2>
                            </div>
                            <a href="#reservation-form-block" class="view-more">
                                Isi Formulir Reservasi <i class="fa-solid fa-arrow-down ms-1"></i>
                            </a>
                        </div>

                        {{-- Panduan Slot Waktu & Ketentuan Layanan --}}
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <div class="reservation-info-tile">
                                    <div>
                                        <div class="reservation-info-tile__icon">
                                            <i class="fa-solid fa-calendar-day"></i>
                                        </div>
                                        <h5 class="fw-bold mb-1">Hari Pelayanan</h5>
                                        <p class="text-muted small mb-2">Jadwal reservasi terbuka untuk hari kerja akademik:</p>
                                        <div class="fw-semibold text-danger">Senin, Selasa, Kamis, Jumat</div>
                                    </div>
                                    <small class="text-secondary mt-2"><i class="fa-solid fa-circle-info me-1"></i>Rabu & akhir pekan tidak tersedia</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="reservation-info-tile">
                                    <div>
                                        <div class="reservation-info-tile__icon">
                                            <i class="fa-solid fa-clock"></i>
                                        </div>
                                        <h5 class="fw-bold mb-1">Pilihan Sesi Pertemuan</h5>
                                        <p class="text-muted small mb-2">Slot waktu yang dapat diajukan (durasi ~2 jam):</p>
                                        <div class="d-flex flex-wrap gap-1">
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">Sesi 1: 09:00 WIB</span>
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">Sesi 2: 13:00 WIB</span>
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">Sesi 3: 15:00 WIB</span>
                                        </div>
                                    </div>
                                    <small class="text-secondary mt-2"><i class="fa-solid fa-users-viewfinder me-1"></i>1 agenda pertemuan per sesi</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="reservation-info-tile">
                                    <div>
                                        <div class="reservation-info-tile__icon">
                                            <i class="fa-solid fa-clipboard-check"></i>
                                        </div>
                                        <h5 class="fw-bold mb-1">Status Ketersediaan Slot</h5>
                                        <p class="text-muted small mb-2">Sesi yang belum terisi pada agenda di bawah berstatus <strong>Bisa Diisi</strong>.</p>
                                        <a href="#reservation-form-block" class="btn btn-sm btn-outline-danger w-100">
                                            <i class="fa-solid fa-pen-to-square me-1"></i> Ajukan Jadwal di Bawah
                                        </a>
                                    </div>
                                    <small class="text-success mt-2"><i class="fa-solid fa-circle-check me-1"></i>Pilih sesi kosong untuk mempercepat persetujuan</small>
                                </div>
                            </div>
                        </div>

                        {{-- Agenda Pertemuan Terjadwal --}}
                        <div class="mt-4 pt-2 border-top">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                                <div>
                                    <h4 class="fw-bold mb-1 text-dark">
                                        <i class="fa-solid fa-list-check text-danger me-2"></i>Daftar Agenda Pertemuan
                                    </h4>
                                    <p class="text-muted small mb-0">Klik pada salah satu agenda untuk melihat informasi lengkap pertemuan.</p>
                                </div>
                                @if (isset($schedules) && $schedules->isNotEmpty())
                                    <span class="badge bg-secondary-subtle text-dark border px-3 py-2">
                                        Total {{ $schedules->count() }} Agenda Terdata
                                    </span>
                                @endif
                            </div>

                            @if (isset($schedules) && $schedules->isNotEmpty())
                                <div class="row g-3">
                                    @foreach ($schedules as $schedule)
                                        <div class="col-md-6 col-lg-4">
                                            <div class="reservation-agenda-card" role="button" data-bs-toggle="modal" data-bs-target="#agendaDetailModal-{{ $schedule->id }}">
                                                <div>
                                                    <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                                                        <span class="reservation-agenda-card__date">
                                                            <i class="fa-solid fa-calendar me-1"></i>{{ \Carbon\Carbon::parse($schedule->date)->translatedFormat('d M Y') }}
                                                        </span>
                                                        <span class="badge bg-danger text-white">
                                                            <i class="fa-solid fa-clock me-1"></i>{{ substr($schedule->shift, 0, 5) }} WIB
                                                        </span>
                                                    </div>

                                                    <h5 class="reservation-agenda-card__title" title="{{ $schedule->agenda }}">
                                                        {{ $schedule->agenda ?: 'Pertemuan dengan Program Studi' }}
                                                    </h5>

                                                    <div class="reservation-agenda-card__meta">
                                                        <i class="fa-solid fa-user"></i>
                                                        <span class="text-dark fw-medium">{{ $schedule->requested_by }}</span>
                                                    </div>

                                                    @if ($schedule->study_program)
                                                        <div class="reservation-agenda-card__meta">
                                                            <i class="fa-solid fa-building-columns"></i>
                                                            <span>{{ $schedule->study_program }}</span>
                                                        </div>
                                                    @endif

                                                    <div class="reservation-agenda-card__meta">
                                                        <i class="fa-solid fa-location-dot"></i>
                                                        <span>{{ $schedule->meeting_room ?: 'Ruang Rapat Prodi' }}</span>
                                                    </div>
                                                </div>

                                                <div class="mt-3 pt-2 border-top">
                                                    <button type="button" class="btn btn-sm btn-outline-danger w-100" data-bs-toggle="modal" data-bs-target="#agendaDetailModal-{{ $schedule->id }}">
                                                        <i class="fa-solid fa-circle-info me-1"></i> Lihat Info Lengkap
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="empty-state text-center p-4">
                                    <i class="fa-regular fa-calendar-check text-danger mb-2" style="font-size: 2.2rem;"></i>
                                    <h5 class="fw-bold text-dark">Belum Ada Agenda Pertemuan Terjadwal</h5>
                                    <p class="text-muted small mb-3">Semua sesi pelayanan (09:00 WIB, 13:00 WIB, dan 15:00 WIB pada hari Senin, Selasa, Kamis, dan Jumat) saat ini berstatus <strong>Bisa Diisi</strong>.</p>
                                    <a href="#reservation-form-block" class="btn btn-sm btn-danger">
                                        <i class="fa-solid fa-calendar-plus me-1"></i> Ajukan Pertemuan Sekarang
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- 2. FORMULIR RESERVASI PERTEMUAN (DITEMPATKAN DI BAWAH INFO JADWAL) --}}
                    <div class="topic-block" id="reservation-form-block">
                        <div class="topic-header">
                            <div class="topic-header__title-wrap">
                                <span class="topic-mark"></span>
                                <h2>Formulir Pengajuan Reservasi</h2>
                            </div>
                            <a href="#reservation-schedule-block" class="view-more">
                                <i class="fa-solid fa-arrow-up me-1"></i> Kembali ke Info Jadwal
                            </a>
                        </div>

                        <p class="text-muted mb-3">Silakan isi formulir di bawah ini dengan memilih tanggal dan sesi yang masih berstatus bisa diisi.</p>

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

    {{-- MODAL DETAIL LENGKAP AGENDA PERTEMUAN --}}
    @if (isset($schedules) && $schedules->isNotEmpty())
        @foreach ($schedules as $schedule)
            <div class="modal fade" id="agendaDetailModal-{{ $schedule->id }}" tabindex="-1" aria-labelledby="agendaDetailModalLabel-{{ $schedule->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content border-0 shadow">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title fw-bold" id="agendaDetailModalLabel-{{ $schedule->id }}">
                                <i class="fa-solid fa-calendar-check me-2"></i> Detail Informasi Agenda Pertemuan
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <div class="mb-4 pb-3 border-bottom">
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle mb-2 px-3 py-2 fw-semibold">
                                    <i class="fa-solid fa-clock me-1"></i> Status: Terjadwal & Terkonfirmasi
                                </span>
                                <h4 class="fw-bold text-dark mb-1">{{ $schedule->agenda ?: 'Pertemuan dengan Program Studi' }}</h4>
                                <p class="text-muted small mb-0"><i class="fa-solid fa-building-columns me-1"></i> Program Studi S1 Informatika Telkom University</p>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="agenda-detail-box">
                                        <span class="text-muted small d-block mb-1"><i class="fa-solid fa-calendar-day me-1 text-danger"></i> Hari & Tanggal Pelaksanaan</span>
                                        <strong class="text-dark fs-6">{{ \Carbon\Carbon::parse($schedule->date)->translatedFormat('l, d F Y') }}</strong>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="agenda-detail-box">
                                        <span class="text-muted small d-block mb-1"><i class="fa-solid fa-clock me-1 text-danger"></i> Waktu / Sesi Pertemuan</span>
                                        <strong class="text-dark fs-6">{{ substr($schedule->shift, 0, 5) }} WIB (Durasi ~2 Jam)</strong>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="agenda-detail-box">
                                        <span class="text-muted small d-block mb-1"><i class="fa-solid fa-user me-1 text-danger"></i> Pemohon / Diajukan Oleh</span>
                                        <strong class="text-dark fs-6">{{ $schedule->requested_by }}</strong>
                                        @if ($schedule->study_program)
                                            <div class="small text-muted">{{ $schedule->study_program }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="agenda-detail-box">
                                        <span class="text-muted small d-block mb-1"><i class="fa-solid fa-location-dot me-1 text-danger"></i> Ruangan / Tempat</span>
                                        <strong class="text-dark fs-6">{{ $schedule->meeting_room ?: 'Ruang Rapat Program Studi S1 Informatika' }}</strong>
                                        @if ($schedule->city)
                                            <div class="small text-muted">{{ $schedule->city }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="agenda-detail-box">
                                        <span class="text-muted small d-block mb-1"><i class="fa-solid fa-users me-1 text-danger"></i> Peserta Pertemuan</span>
                                        <strong class="text-dark fs-6">{{ $schedule->participants ?: 'Sesuai daftar hadir' }}</strong>
                                    </div>
                                </div>
                                @if ($schedule->prodi_signature_name || $schedule->related_party_signature_name)
                                    <div class="col-md-6">
                                        <div class="agenda-detail-box">
                                            <span class="text-muted small d-block mb-1"><i class="fa-solid fa-file-signature me-1 text-danger"></i> Penandatangan Dokumen</span>
                                            @if ($schedule->prodi_signature_name)
                                                <strong class="d-block text-dark">{{ $schedule->prodi_signature_name }}</strong>
                                                <small class="text-muted">{{ $schedule->prodi_signature_position ?: 'Program Studi' }}</small>
                                            @endif
                                            @if ($schedule->related_party_signature_name)
                                                <div class="mt-1 pt-1 border-top">
                                                    <strong class="d-block text-dark">{{ $schedule->related_party_signature_name }}</strong>
                                                    <small class="text-muted">{{ $schedule->related_party_signature_position ?: 'Pihak Terkait' }}</small>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                                <div class="col-12">
                                    <div class="agenda-detail-box">
                                        <span class="text-muted small d-block mb-1"><i class="fa-solid fa-align-left me-1 text-danger"></i> Deskripsi & Rincian Agenda</span>
                                        <p class="text-dark mb-0" style="white-space: pre-line;">{{ $schedule->agenda }}</p>
                                    </div>
                                </div>
                                @if ($schedule->document_link)
                                    <div class="col-12">
                                        <div class="alert alert-info d-flex align-items-center justify-content-between mb-0">
                                            <div>
                                                <i class="fa-regular fa-file-pdf text-danger me-2 fs-5"></i>
                                                <strong>Dokumen Berita Acara / Lampiran Tersedia</strong>
                                            </div>
                                            <a href="{{ $schedule->document_link }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-danger">
                                                <i class="fa-solid fa-download me-1"></i> Buka Dokumen PDF
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <a href="#reservation-form-block" class="btn btn-danger" data-bs-dismiss="modal">
                                <i class="fa-solid fa-calendar-plus me-1"></i> Ajukan Jadwal di Sesi Lain
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
@endsection