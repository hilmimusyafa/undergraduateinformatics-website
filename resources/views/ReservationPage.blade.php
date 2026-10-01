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
                            <div class="d-flex align-items-center gap-2">
                                <div class="btn-group btn-group-sm" role="group" aria-label="Pilihan Tampilan">
                                    <button type="button" class="btn btn-outline-danger active" id="btnToggleCalendar">
                                        <i class="fa-solid fa-calendar-days me-1"></i> Kalender
                                    </button>
                                    <button type="button" class="btn btn-outline-danger" id="btnToggleList">
                                        <i class="fa-solid fa-list-check me-1"></i> Daftar
                                    </button>
                                </div>
                                <a href="#reservation-form-block" class="view-more ms-2 d-none d-md-inline">
                                    Isi Formulir Reservasi <i class="fa-solid fa-arrow-down ms-1"></i>
                                </a>
                            </div>
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

                        {{-- TAMPILAN 1: KALENDER INTERAKTIF --}}
                        <div id="reservationCalendarSection" class="mt-4 pt-2 border-top">
                            <div class="reservation-calendar">
                                <div class="calendar-topbar">
                                    <div class="d-flex align-items-center gap-2">
                                        <h3 id="calMonthTitle" class="calendar-month-title mb-0">Oktober 2026</h3>
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle ms-2" id="calEventCountBadge">
                                            {{ isset($schedules) ? $schedules->count() : 0 }} Agenda Terdata
                                        </span>
                                    </div>
                                    <div class="d-flex align-items-center gap-1">
                                        <button type="button" class="calendar-nav-btn" id="calPrevBtn" title="Bulan Sebelumnya">
                                            <i class="fa-solid fa-chevron-left me-1"></i> Sebelumnya
                                        </button>
                                        <button type="button" class="calendar-nav-btn" id="calTodayBtn">
                                            Hari Ini
                                        </button>
                                        <button type="button" class="calendar-nav-btn" id="calNextBtn" title="Bulan Berikutnya">
                                            Berikutnya <i class="fa-solid fa-chevron-right ms-1"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="calendar-legend">
                                    <span class="calendar-legend-item">
                                        <span class="calendar-legend-dot calendar-legend-dot--event"></span>
                                        <strong>Ada Agenda Terjadwal</strong> (klik kartu/pill untuk lihat detail)
                                    </span>
                                    <span class="calendar-legend-item">
                                        <span class="calendar-legend-dot calendar-legend-dot--available"></span>
                                        <strong>Bisa Diisi</strong> (Senin, Selasa, Kamis, Jumat)
                                    </span>
                                    <span class="calendar-legend-item">
                                        <span class="calendar-legend-dot calendar-legend-dot--off"></span>
                                        <span class="text-muted">Non-Layanan (Rabu & Akhir Pekan)</span>
                                    </span>
                                </div>

                                <div class="calendar-grid mb-1">
                                    <div class="calendar-grid-header">Senin</div>
                                    <div class="calendar-grid-header">Selasa</div>
                                    <div class="calendar-grid-header text-muted">Rabu</div>
                                    <div class="calendar-grid-header">Kamis</div>
                                    <div class="calendar-grid-header">Jumat</div>
                                    <div class="calendar-grid-header text-muted">Sabtu</div>
                                    <div class="calendar-grid-header text-muted">Minggu</div>
                                </div>

                                <div class="calendar-grid" id="calendarGridBody">
                                    {{-- Kalender di-render oleh script di bawah --}}
                                </div>

                                {{-- Panel Rincian Tanggal Terpilih --}}
                                <div class="calendar-selected-panel mt-3" id="calendarSelectedPanel">
                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2 pb-2 border-bottom">
                                        <div>
                                            <span class="text-danger small fw-bold text-uppercase"><i class="fa-solid fa-calendar-day me-1"></i> Tanggal Terpilih</span>
                                            <h5 class="fw-bold text-dark mb-0" id="selectedDateTitle">Pilih tanggal pada kalender</h5>
                                        </div>
                                        <div id="selectedDateBadge"></div>
                                    </div>
                                    <div id="selectedDateContent">
                                        <p class="text-muted small mb-0">Klik pada salah satu tanggal di kalender di atas untuk melihat rincian agenda atau ketersediaan sesi pada hari tersebut.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- TAMPILAN 2: DAFTAR AGENDA (TOGGLEABLE) --}}
                        <div id="reservationListSection" class="mt-4 pt-2 border-top d-none">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                                <div>
                                    <h4 class="fw-bold mb-1 text-dark">
                                        <i class="fa-solid fa-list-check text-danger me-2"></i>Daftar Seluruh Agenda Pertemuan
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const schedules = {!! json_encode(isset($schedules) ? $schedules->map(function($s) {
        return [
            'id' => $s->id,
            'date' => $s->date,
            'shift' => substr($s->shift, 0, 5),
            'requested_by' => $s->requested_by,
            'study_program' => $s->study_program,
            'meeting_room' => $s->meeting_room,
            'participants' => $s->participants,
            'agenda' => $s->agenda,
            'city' => $s->city,
            'formatted_date' => \Carbon\Carbon::parse($s->date)->translatedFormat('l, d F Y'),
        ];
    }) : []) !!};

    const allowedShifts = ['09:00', '13:00', '15:00'];
    const monthNames = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    const dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

    let currentDate = new Date();
    if (schedules.length > 0) {
        const firstEventDate = new Date(schedules[0].date);
        if (!isNaN(firstEventDate.getTime())) {
            currentDate = firstEventDate;
        }
    }

    let viewYear = currentDate.getFullYear();
    let viewMonth = currentDate.getMonth();
    let selectedDateStr = null;

    const monthTitleEl = document.getElementById('calMonthTitle');
    const gridBodyEl = document.getElementById('calendarGridBody');
    const selectedTitleEl = document.getElementById('selectedDateTitle');
    const selectedBadgeEl = document.getElementById('selectedDateBadge');
    const selectedContentEl = document.getElementById('selectedDateContent');
    const prevBtn = document.getElementById('calPrevBtn');
    const nextBtn = document.getElementById('calNextBtn');
    const todayBtn = document.getElementById('calTodayBtn');

    const btnToggleCalendar = document.getElementById('btnToggleCalendar');
    const btnToggleList = document.getElementById('btnToggleList');
    const calendarSection = document.getElementById('reservationCalendarSection');
    const listSection = document.getElementById('reservationListSection');

    if (btnToggleCalendar && btnToggleList) {
        btnToggleCalendar.addEventListener('click', function () {
            btnToggleCalendar.classList.add('active');
            btnToggleList.classList.remove('active');
            if (calendarSection) calendarSection.classList.remove('d-none');
            if (listSection) listSection.classList.add('d-none');
        });

        btnToggleList.addEventListener('click', function () {
            btnToggleList.classList.add('active');
            btnToggleCalendar.classList.remove('active');
            if (listSection) listSection.classList.remove('d-none');
            if (calendarSection) calendarSection.classList.add('d-none');
        });
    }

    function formatZero(num) {
        return num < 10 ? '0' + num : '' + num;
    }

    function renderCalendar() {
        if (!gridBodyEl || !monthTitleEl) return;

        monthTitleEl.textContent = monthNames[viewMonth] + ' ' + viewYear;
        gridBodyEl.innerHTML = '';

        const firstDayIndex = new Date(viewYear, viewMonth, 1).getDay();
        const mondayStartIndex = (firstDayIndex + 6) % 7;

        const totalDaysInMonth = new Date(viewYear, viewMonth + 1, 0).getDate();
        const totalDaysPrevMonth = new Date(viewYear, viewMonth, 0).getDate();

        const today = new Date();
        const todayStr = today.getFullYear() + '-' + formatZero(today.getMonth() + 1) + '-' + formatZero(today.getDate());

        for (let i = mondayStartIndex - 1; i >= 0; i--) {
            const dayNum = totalDaysPrevMonth - i;
            const cell = document.createElement('div');
            cell.className = 'calendar-cell calendar-cell--muted';
            cell.innerHTML = `
                <div class="calendar-cell__header">
                    <span class="calendar-cell__number">${dayNum}</span>
                </div>
            `;
            gridBodyEl.appendChild(cell);
        }

        for (let day = 1; day <= totalDaysInMonth; day++) {
            const dateStr = viewYear + '-' + formatZero(viewMonth + 1) + '-' + formatZero(day);
            const dateObj = new Date(viewYear, viewMonth, day);
            const dayOfWeek = dateObj.getDay();
            const isOperational = [1, 2, 4, 5].includes(dayOfWeek);
            const isToday = (dateStr === todayStr);
            const isSelected = (dateStr === selectedDateStr);

            const dayEvents = schedules.filter(s => s.date === dateStr);

            const cell = document.createElement('div');
            let cellClasses = ['calendar-cell'];

            if (isToday) cellClasses.push('calendar-cell--today');
            if (isSelected) cellClasses.push('calendar-cell--selected');
            if (!isOperational) cellClasses.push('calendar-cell--non-operational');

            cell.className = cellClasses.join(' ');
            cell.setAttribute('data-date', dateStr);

            let eventsHtml = '';
            let dotHtml = '';

            if (dayEvents.length > 0) {
                dayEvents.forEach(evt => {
                    eventsHtml += `
                        <div class="calendar-event-pill" data-schedule-id="${evt.id}" title="${evt.agenda || 'Pertemuan'}">
                            <span class="calendar-event-pill__time">${evt.shift}</span>
                            <span>${evt.agenda || 'Pertemuan'}</span>
                        </div>
                    `;
                });
                dotHtml = `<span class="calendar-cell__event-dot" title="${dayEvents.length} agenda"></span>`;
            } else if (isOperational) {
                eventsHtml = `<span class="calendar-cell__status text-success d-none d-md-block mt-auto"><i class="fa-solid fa-check small me-1"></i>Bisa Diisi</span>`;
            } else {
                eventsHtml = `<span class="calendar-cell__status text-muted d-none d-md-block mt-auto small">Non-Layanan</span>`;
            }

            cell.innerHTML = `
                <div class="calendar-cell__header">
                    <span class="calendar-cell__number">${day}</span>
                    ${dayEvents.length > 0 ? '<span class="badge bg-danger text-white rounded-pill d-none d-md-inline" style="font-size:0.62rem;">' + dayEvents.length + ' Agenda</span>' : ''}
                </div>
                ${dotHtml}
                <div class="mt-1 flex-grow-1 d-flex flex-column justify-content-start overflow-hidden">
                    ${eventsHtml}
                </div>
            `;

            cell.addEventListener('click', function (e) {
                const pill = e.target.closest('.calendar-event-pill');
                if (pill) {
                    const schedId = pill.getAttribute('data-schedule-id');
                    openAgendaModal(schedId);
                    e.stopPropagation();
                    return;
                }

                selectDate(dateStr, dayOfWeek, dayEvents, isOperational);
            });

            gridBodyEl.appendChild(cell);
        }

        if (!selectedDateStr) {
            const firstEventInMonth = schedules.find(s => s.date.startsWith(viewYear + '-' + formatZero(viewMonth + 1)));
            if (firstEventInMonth) {
                const parts = firstEventInMonth.date.split('-');
                const dObj = new Date(parts[0], parts[1] - 1, parts[2]);
                const dayEvents = schedules.filter(s => s.date === firstEventInMonth.date);
                selectDate(firstEventInMonth.date, dObj.getDay(), dayEvents, [1, 2, 4, 5].includes(dObj.getDay()));
            } else {
                const dObj = new Date(viewYear, viewMonth, 1);
                const firstDayStr = viewYear + '-' + formatZero(viewMonth + 1) + '-01';
                selectDate(firstDayStr, dObj.getDay(), [], [1, 2, 4, 5].includes(dObj.getDay()));
            }
        }
    }

    function selectDate(dateStr, dayOfWeek, dayEvents, isOperational) {
        selectedDateStr = dateStr;

        document.querySelectorAll('.calendar-cell').forEach(c => {
            if (c.getAttribute('data-date') === dateStr) {
                c.classList.add('calendar-cell--selected');
            } else {
                c.classList.remove('calendar-cell--selected');
            }
        });

        const parts = dateStr.split('-');
        const dateObj = new Date(parts[0], parts[1] - 1, parts[2]);
        const dayName = dayNames[dateObj.getDay()];
        const monthName = monthNames[dateObj.getMonth()];
        const formattedDate = dayName + ', ' + parseInt(parts[2], 10) + ' ' + monthName + ' ' + parts[0];

        if (selectedTitleEl) selectedTitleEl.textContent = formattedDate;

        if (selectedBadgeEl) {
            if (!isOperational) {
                selectedBadgeEl.innerHTML = '<span class="badge bg-secondary">Hari Non-Pelayanan</span>';
            } else if (dayEvents.length > 0) {
                selectedBadgeEl.innerHTML = '<span class="badge bg-danger">' + dayEvents.length + ' Agenda Terjadwal</span>';
            } else {
                selectedBadgeEl.innerHTML = '<span class="badge bg-success">Slot Tersedia (Bisa Diisi)</span>';
            }
        }

        if (selectedContentEl) {
            let html = '';

            if (dayEvents.length > 0) {
                html += '<div class="row g-2 mb-3">';
                dayEvents.forEach(evt => {
                    html += `
                        <div class="col-md-6">
                            <div class="card h-100 border p-3 rounded-3 shadow-sm bg-white">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="badge bg-danger text-white"><i class="fa-solid fa-clock me-1"></i>${evt.shift} WIB</span>
                                    <small class="text-muted"><i class="fa-solid fa-location-dot me-1"></i>${evt.meeting_room || 'Ruang Prodi'}</small>
                                </div>
                                <h6 class="fw-bold text-dark mb-1">${evt.agenda || 'Pertemuan'}</h6>
                                <p class="text-muted small mb-2"><i class="fa-solid fa-user me-1"></i>${evt.requested_by} ${evt.study_program ? '(' + evt.study_program + ')' : ''}</p>
                                <button type="button" class="btn btn-sm btn-outline-danger mt-auto" onclick="window.openAgendaModal(${evt.id})">
                                    <i class="fa-solid fa-circle-info me-1"></i> Lihat Info Lengkap
                                </button>
                            </div>
                        </div>
                    `;
                });
                html += '</div>';

                const occupiedShifts = dayEvents.map(e => e.shift);
                const freeShifts = allowedShifts.filter(s => !occupiedShifts.includes(s));
                if (freeShifts.length > 0 && isOperational) {
                    html += `
                        <div class="alert alert-success d-flex align-items-center justify-content-between flex-wrap gap-2 mb-0 py-2 px-3">
                            <div>
                                <i class="fa-solid fa-circle-check text-success me-1"></i>
                                <strong>Sesi lain yang masih bisa diisi:</strong> ${freeShifts.map(s => s + ' WIB').join(', ')}
                            </div>
                            <a href="#reservation-form-block" class="btn btn-sm btn-success">
                                <i class="fa-solid fa-pen-to-square me-1"></i> Isi Form untuk Sesi Ini
                            </a>
                        </div>
                    `;
                }
            } else if (isOperational) {
                html += `
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 p-3 bg-white border rounded-3">
                        <div>
                            <h6 class="fw-bold text-success mb-1"><i class="fa-solid fa-calendar-check me-2"></i>Semua Sesi Tersedia (Bisa Diisi)</h6>
                            <p class="text-muted small mb-0">Pada tanggal ini tersedia Sesi 1 (09:00 WIB), Sesi 2 (13:00 WIB), dan Sesi 3 (15:00 WIB).</p>
                        </div>
                        <a href="#reservation-form-block" class="btn btn-sm btn-danger">
                            <i class="fa-solid fa-calendar-plus me-1"></i> Ajukan Jadwal di Tanggal Ini
                        </a>
                    </div>
                `;
            } else {
                html += `
                    <div class="p-3 bg-white border rounded-3 text-muted small">
                        <i class="fa-solid fa-circle-exclamation text-secondary me-1"></i>
                        Tanggal ini merupakan hari <strong>Rabu / Akhir Pekan</strong>. Layanan reservasi pertemuan Program Studi hanya dibuka pada hari <strong>Senin, Selasa, Kamis, dan Jumat</strong>. Silakan pilih hari layanan lainnya.
                    </div>
                `;
            }

            selectedContentEl.innerHTML = html;
        }
    }

    function openAgendaModal(id) {
        const modalEl = document.getElementById('agendaDetailModal-' + id);
        if (modalEl && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            const modalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
            modalInstance.show();
        }
    }

    window.openAgendaModal = openAgendaModal;

    if (prevBtn) {
        prevBtn.addEventListener('click', function () {
            viewMonth--;
            if (viewMonth < 0) {
                viewMonth = 11;
                viewYear--;
            }
            selectedDateStr = null;
            renderCalendar();
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', function () {
            viewMonth++;
            if (viewMonth > 11) {
                viewMonth = 0;
                viewYear++;
            }
            selectedDateStr = null;
            renderCalendar();
        });
    }

    if (todayBtn) {
        todayBtn.addEventListener('click', function () {
            const now = new Date();
            viewYear = now.getFullYear();
            viewMonth = now.getMonth();
            selectedDateStr = null;
            renderCalendar();
        });
    }

    renderCalendar();
});
</script>
@endpush
@endsection