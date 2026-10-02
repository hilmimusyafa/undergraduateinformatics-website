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

                        {{-- KALENDER INTERAKTIF AGENDA PERTEMUAN --}}
                        <div id="reservationCalendarSection" class="mt-4 pt-2 border-top">
                            <div class="reservation-calendar">
                                <div class="calendar-topbar">
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <div class="btn-group btn-group-sm calendar-view-toggle" role="group" aria-label="Pilihan Tampilan Kalender">
                                            <button type="button" class="btn btn-outline-danger active" id="calModeWeekBtn">
                                                <i class="fa-solid fa-calendar-week me-1"></i> Kalender Mingguan
                                            </button>
                                            <button type="button" class="btn btn-outline-danger" id="calModeMonthBtn">
                                                <i class="fa-solid fa-calendar-days me-1"></i> Preview Bulanan
                                            </button>
                                        </div>
                                        <h3 id="calMonthTitle" class="calendar-month-title mb-0 ms-md-2">Oktober 2026</h3>
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle" id="calEventCountBadge">
                                            {{ isset($schedules) ? $schedules->count() : 0 }} Agenda
                                        </span>
                                    </div>
                                    <div class="d-flex align-items-center gap-1">
                                        <button type="button" class="calendar-nav-btn" id="calPrevBtn" title="Sebelumnya">
                                            <i class="fa-solid fa-chevron-left me-1"></i> Sebelumnya
                                        </button>
                                        <button type="button" class="calendar-nav-btn" id="calTodayBtn">
                                            Hari Ini
                                        </button>
                                        <button type="button" class="calendar-nav-btn" id="calNextBtn" title="Berikutnya">
                                            Berikutnya <i class="fa-solid fa-chevron-right ms-1"></i>
                                        </button>
                                    </div>
                                </div>

                                {{-- Legend --}}
                                <div class="calendar-legend" id="calendarLegend">
                                    <span class="calendar-legend-item">
                                        <span class="calendar-legend-dot calendar-legend-dot--event"></span>
                                        <strong id="legendEventText">Terjadwal</strong>
                                    </span>
                                    <span class="calendar-legend-item" id="legendAvailableItem">
                                        <span class="calendar-legend-dot calendar-legend-dot--available"></span>
                                        <strong>Tersedia</strong>
                                    </span>
                                    <span class="calendar-legend-item" id="legendOffItem">
                                        <span class="calendar-legend-dot calendar-legend-dot--off"></span>
                                        <span class="text-muted">Non-Layanan</span>
                                    </span>
                                </div>

                                {{-- TAMPILAN 1: KALENDER MINGGUAN (DEFAULT, SIMPLE & MINIMALIS) --}}
                                <div id="calendarWeekContainer">
                                    <div class="calendar-grid-wrapper">
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
                                            {{-- 7 kotak hari minggu ini di-render oleh script --}}
                                        </div>
                                    </div>
                                </div>

                                {{-- TAMPILAN 2: PREVIEW BULANAN DENGAN TITIK (MINIMALIS) --}}
                                <div id="calendarMonthPreviewContainer" class="d-none">
                                    <div class="alert alert-light border d-flex align-items-center justify-content-between flex-wrap gap-2 py-2 px-3 mb-3">
                                        <div class="small text-muted">
                                            <i class="fa-solid fa-circle-info text-danger me-1"></i>
                                            <strong>Preview Bulanan:</strong> Klik pada tanggal untuk langsung membuka kalender minggu tersebut. Titik merah (<span class="calendar-legend-dot calendar-legend-dot--event d-inline-block" style="width:8px;height:8px;vertical-align:middle;"></span>) menandakan tanggal dengan agenda.
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-danger" id="btnBackToWeek">
                                            <i class="fa-solid fa-calendar-week me-1"></i> Buka Kalender Mingguan
                                        </button>
                                    </div>

                                    <div class="calendar-grid-wrapper">
                                        <div class="calendar-grid mb-1">
                                            <div class="calendar-grid-header">Sen</div>
                                            <div class="calendar-grid-header">Sel</div>
                                            <div class="calendar-grid-header text-muted">Rab</div>
                                            <div class="calendar-grid-header">Kam</div>
                                            <div class="calendar-grid-header">Jum</div>
                                            <div class="calendar-grid-header text-muted">Sab</div>
                                            <div class="calendar-grid-header text-muted">Min</div>
                                        </div>

                                        <div class="calendar-grid month-preview-grid" id="monthPreviewGridBody">
                                            {{-- Grid bulanan dengan info titik di-render oleh script --}}
                                        </div>
                                    </div>
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
    const monthNamesShort = [
        'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
        'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
    ];
    const dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

    function formatZero(num) {
        return num < 10 ? '0' + num : '' + num;
    }

    function toDateStr(d) {
        return d.getFullYear() + '-' + formatZero(d.getMonth() + 1) + '-' + formatZero(d.getDate());
    }

    function getMonday(d) {
        const date = new Date(d.getFullYear(), d.getMonth(), d.getDate());
        const day = date.getDay();
        const diff = (day === 0 ? -6 : 1) - day;
        date.setDate(date.getDate() + diff);
        date.setHours(0, 0, 0, 0);
        return date;
    }

    function addDays(d, n) {
        const res = new Date(d.getFullYear(), d.getMonth(), d.getDate());
        res.setDate(res.getDate() + n);
        return res;
    }

    function shortenText(str, maxLength = 14) {
        if (!str) return 'Agenda';
        str = str.trim();
        return str.length > maxLength ? str.substring(0, maxLength) + '...' : str;
    }

    let currentDate = new Date();
    if (schedules.length > 0) {
        const firstEventDate = new Date(schedules[0].date);
        if (!isNaN(firstEventDate.getTime())) {
            currentDate = firstEventDate;
        }
    }

    let viewMode = 'week';
    let currentWeekMonday = getMonday(currentDate);
    let viewYear = currentWeekMonday.getFullYear();
    let viewMonth = currentWeekMonday.getMonth();
    let selectedDateStr = schedules.length > 0 ? schedules[0].date : toDateStr(currentDate);

    const monthTitleEl = document.getElementById('calMonthTitle');
    const eventCountBadgeEl = document.getElementById('calEventCountBadge');
    const gridBodyEl = document.getElementById('calendarGridBody');
    const monthPreviewGridEl = document.getElementById('monthPreviewGridBody');
    const weekContainerEl = document.getElementById('calendarWeekContainer');
    const monthContainerEl = document.getElementById('calendarMonthPreviewContainer');
    const selectedTitleEl = document.getElementById('selectedDateTitle');
    const selectedBadgeEl = document.getElementById('selectedDateBadge');
    const selectedContentEl = document.getElementById('selectedDateContent');
    const prevBtn = document.getElementById('calPrevBtn');
    const nextBtn = document.getElementById('calNextBtn');
    const todayBtn = document.getElementById('calTodayBtn');
    const modeWeekBtn = document.getElementById('calModeWeekBtn');
    const modeMonthBtn = document.getElementById('calModeMonthBtn');
    const backToWeekBtn = document.getElementById('btnBackToWeek');
    const legendAvailableItem = document.getElementById('legendAvailableItem');
    const legendOffItem = document.getElementById('legendOffItem');
    const legendEventText = document.getElementById('legendEventText');

    function updateNavButtons() {
        if (!prevBtn || !nextBtn || !todayBtn) return;
        if (viewMode === 'week') {
            prevBtn.innerHTML = '<i class="fa-solid fa-chevron-left me-1"></i> Minggu Lalu';
            prevBtn.title = 'Minggu Sebelumnya';
            todayBtn.textContent = 'Minggu Ini';
            nextBtn.innerHTML = 'Minggu Depan <i class="fa-solid fa-chevron-right ms-1"></i>';
            nextBtn.title = 'Minggu Berikutnya';
            if (legendAvailableItem) legendAvailableItem.classList.remove('d-none');
            if (legendOffItem) legendOffItem.classList.remove('d-none');
            if (legendEventText) legendEventText.textContent = 'Terjadwal';
        } else {
            prevBtn.innerHTML = '<i class="fa-solid fa-chevron-left me-1"></i> Bulan Lalu';
            prevBtn.title = 'Bulan Sebelumnya';
            todayBtn.textContent = 'Bulan Ini';
            nextBtn.innerHTML = 'Bulan Depan <i class="fa-solid fa-chevron-right ms-1"></i>';
            nextBtn.title = 'Bulan Berikutnya';
            if (legendAvailableItem) legendAvailableItem.classList.add('d-none');
            if (legendOffItem) legendOffItem.classList.remove('d-none');
            if (legendEventText) legendEventText.textContent = 'Ada Agenda (Titik)';
        }
    }

    function setViewMode(mode) {
        viewMode = mode;
        if (mode === 'week') {
            if (modeWeekBtn) modeWeekBtn.classList.add('active');
            if (modeMonthBtn) modeMonthBtn.classList.remove('active');
            if (weekContainerEl) weekContainerEl.classList.remove('d-none');
            if (monthContainerEl) monthContainerEl.classList.add('d-none');
            updateNavButtons();
            renderWeekView();
        } else {
            if (modeMonthBtn) modeMonthBtn.classList.add('active');
            if (modeWeekBtn) modeWeekBtn.classList.remove('active');
            if (weekContainerEl) weekContainerEl.classList.add('d-none');
            if (monthContainerEl) monthContainerEl.classList.remove('d-none');
            viewYear = currentWeekMonday.getFullYear();
            viewMonth = currentWeekMonday.getMonth();
            updateNavButtons();
            renderMonthPreview();
        }
    }

    function renderWeekView() {
        if (!gridBodyEl || !monthTitleEl) return;

        const sunday = addDays(currentWeekMonday, 6);
        const monYear = currentWeekMonday.getFullYear();
        const monMonth = currentWeekMonday.getMonth();
        const sunYear = sunday.getFullYear();
        const sunMonth = sunday.getMonth();

        let weekTitle = '';
        if (monYear === sunYear && monMonth === sunMonth) {
            weekTitle = currentWeekMonday.getDate() + ' - ' + sunday.getDate() + ' ' + monthNames[monMonth] + ' ' + monYear;
        } else if (monYear === sunYear) {
            weekTitle = currentWeekMonday.getDate() + ' ' + monthNamesShort[monMonth] + ' - ' + sunday.getDate() + ' ' + monthNames[sunMonth] + ' ' + monYear;
        } else {
            weekTitle = currentWeekMonday.getDate() + ' ' + monthNamesShort[monMonth] + ' ' + monYear + ' - ' + sunday.getDate() + ' ' + monthNamesShort[sunMonth] + ' ' + sunYear;
        }
        monthTitleEl.textContent = weekTitle;

        gridBodyEl.innerHTML = '';
        const today = new Date();
        const todayStr = toDateStr(today);

        let weekEventsTotal = 0;
        let weekDates = [];

        for (let i = 0; i < 7; i++) {
            const dayDate = addDays(currentWeekMonday, i);
            const dateStr = toDateStr(dayDate);
            weekDates.push(dateStr);

            const dayNum = dayDate.getDate();
            const dayOfWeek = dayDate.getDay();
            const isOperational = [1, 2, 4, 5].includes(dayOfWeek);
            const isToday = (dateStr === todayStr);
            const isSelected = (dateStr === selectedDateStr);

            const dayEvents = schedules.filter(s => s.date === dateStr);
            weekEventsTotal += dayEvents.length;

            const cell = document.createElement('div');
            let cellClasses = ['calendar-cell', 'calendar-cell--week'];
            if (isToday) cellClasses.push('calendar-cell--today');
            if (isSelected) cellClasses.push('calendar-cell--selected');
            if (!isOperational) cellClasses.push('calendar-cell--non-operational');

            cell.className = cellClasses.join(' ');
            cell.setAttribute('data-date', dateStr);

            let eventsHtml = '';
            if (dayEvents.length > 0) {
                dayEvents.forEach(evt => {
                    eventsHtml += `
                        <div class="calendar-event-pill" data-schedule-id="${evt.id}" title="${evt.agenda || 'Pertemuan'}">
                            <span class="calendar-event-pill__time">${evt.shift}</span>
                            <span class="calendar-event-pill__text">${shortenText(evt.agenda, 14)}</span>
                        </div>
                    `;
                });
            } else if (isOperational) {
                eventsHtml = `<span class="calendar-cell__status text-success d-flex align-items-center gap-1 mt-auto" style="font-size:0.68rem; font-weight:600;"><i class="fa-solid fa-circle text-success" style="font-size:4px;"></i>Tersedia</span>`;
            } else {
                eventsHtml = `<span class="calendar-cell__status text-muted mt-auto" style="font-size:0.68rem;">Non-Layanan</span>`;
            }

            cell.innerHTML = `
                <div class="calendar-cell__header">
                    <span class="calendar-cell__number">${dayNum}</span>
                    <span class="calendar-cell__dayname">${dayNames[dayOfWeek]}</span>
                </div>
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

        if (eventCountBadgeEl) {
            eventCountBadgeEl.textContent = weekEventsTotal + ' Agenda Minggu Ini';
        }

        if (!weekDates.includes(selectedDateStr)) {
            const dayWithEvent = weekDates.find(d => schedules.some(s => s.date === d));
            const targetDateStr = dayWithEvent || weekDates[0];
            const p = targetDateStr.split('-');
            const dObj = new Date(p[0], p[1] - 1, p[2]);
            const dEvents = schedules.filter(s => s.date === targetDateStr);
            selectDate(targetDateStr, dObj.getDay(), dEvents, [1, 2, 4, 5].includes(dObj.getDay()));
        } else {
            const p = selectedDateStr.split('-');
            const dObj = new Date(p[0], p[1] - 1, p[2]);
            const dEvents = schedules.filter(s => s.date === selectedDateStr);
            selectDate(selectedDateStr, dObj.getDay(), dEvents, [1, 2, 4, 5].includes(dObj.getDay()));
        }
    }

    function renderMonthPreview() {
        if (!monthPreviewGridEl || !monthTitleEl) return;

        monthTitleEl.textContent = monthNames[viewMonth] + ' ' + viewYear;
        monthPreviewGridEl.innerHTML = '';

        const firstDayIndex = new Date(viewYear, viewMonth, 1).getDay();
        const mondayStartIndex = (firstDayIndex + 6) % 7;

        const totalDaysInMonth = new Date(viewYear, viewMonth + 1, 0).getDate();
        const totalDaysPrevMonth = new Date(viewYear, viewMonth, 0).getDate();

        const today = new Date();
        const todayStr = toDateStr(today);

        const currentWeekEnd = addDays(currentWeekMonday, 6);
        const currentWeekMondayStr = toDateStr(currentWeekMonday);
        const currentWeekSundayStr = toDateStr(currentWeekEnd);

        let monthEventsTotal = 0;

        for (let i = mondayStartIndex - 1; i >= 0; i--) {
            const prevDayNum = totalDaysPrevMonth - i;
            const prevMonthDate = new Date(viewYear, viewMonth - 1, prevDayNum);
            const prevDateStr = toDateStr(prevMonthDate);

            const cell = document.createElement('div');
            cell.className = 'month-preview-cell month-preview-cell--muted';
            cell.title = 'Buka minggu ' + prevDateStr;
            cell.innerHTML = `
                <span class="month-preview-cell__number">${prevDayNum}</span>
                <div class="month-preview-dots"></div>
            `;
            cell.addEventListener('click', function () {
                currentWeekMonday = getMonday(prevMonthDate);
                selectedDateStr = prevDateStr;
                setViewMode('week');
            });
            monthPreviewGridEl.appendChild(cell);
        }

        for (let day = 1; day <= totalDaysInMonth; day++) {
            const dateObj = new Date(viewYear, viewMonth, day);
            const dateStr = toDateStr(dateObj);
            const dayOfWeek = dateObj.getDay();
            const isOperational = [1, 2, 4, 5].includes(dayOfWeek);
            const isToday = (dateStr === todayStr);
            const isSelected = (dateStr === selectedDateStr);
            const isCurrentWeek = (dateStr >= currentWeekMondayStr && dateStr <= currentWeekSundayStr);

            const dayEvents = schedules.filter(s => s.date === dateStr);
            monthEventsTotal += dayEvents.length;

            const cell = document.createElement('div');
            let cellClasses = ['month-preview-cell'];
            if (isToday) cellClasses.push('calendar-cell--today');
            if (isSelected) cellClasses.push('month-preview-cell--selected');
            if (isCurrentWeek) cellClasses.push('month-preview-cell--active-week');
            if (!isOperational) cellClasses.push('month-preview-cell--non-operational');

            cell.className = cellClasses.join(' ');
            cell.setAttribute('data-date', dateStr);
            cell.title = dayNames[dayOfWeek] + ', ' + day + ' ' + monthNames[viewMonth] + ' (Klik untuk buka jadwal mingguan)';

            let dotsHtml = '';
            if (dayEvents.length > 0) {
                const dotCount = Math.min(dayEvents.length, 3);
                for (let k = 0; k < dotCount; k++) {
                    dotsHtml += `<span class="month-preview-dot month-preview-dot--event"></span>`;
                }
            }

            cell.innerHTML = `
                <span class="month-preview-cell__number">${day}</span>
                <div class="month-preview-dots">
                    ${dotsHtml}
                </div>
            `;

            cell.addEventListener('click', function () {
                currentWeekMonday = getMonday(dateObj);
                selectedDateStr = dateStr;
                setViewMode('week');
            });

            monthPreviewGridEl.appendChild(cell);
        }

        const totalRendered = mondayStartIndex + totalDaysInMonth;
        const remainingCells = (7 - (totalRendered % 7)) % 7;
        for (let nextDay = 1; nextDay <= remainingCells; nextDay++) {
            const nextMonthDate = new Date(viewYear, viewMonth + 1, nextDay);
            const nextDateStr = toDateStr(nextMonthDate);

            const cell = document.createElement('div');
            cell.className = 'month-preview-cell month-preview-cell--muted';
            cell.title = 'Buka minggu ' + nextDateStr;
            cell.innerHTML = `
                <span class="month-preview-cell__number">${nextDay}</span>
                <div class="month-preview-dots"></div>
            `;
            cell.addEventListener('click', function () {
                currentWeekMonday = getMonday(nextMonthDate);
                selectedDateStr = nextDateStr;
                setViewMode('week');
            });
            monthPreviewGridEl.appendChild(cell);
        }

        if (eventCountBadgeEl) {
            eventCountBadgeEl.textContent = monthEventsTotal + ' Agenda Bulan Ini';
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

    if (modeWeekBtn) {
        modeWeekBtn.addEventListener('click', function () {
            setViewMode('week');
        });
    }

    if (modeMonthBtn) {
        modeMonthBtn.addEventListener('click', function () {
            setViewMode('month');
        });
    }

    if (backToWeekBtn) {
        backToWeekBtn.addEventListener('click', function () {
            setViewMode('week');
        });
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', function () {
            if (viewMode === 'week') {
                currentWeekMonday = addDays(currentWeekMonday, -7);
                renderWeekView();
            } else {
                viewMonth--;
                if (viewMonth < 0) {
                    viewMonth = 11;
                    viewYear--;
                }
                renderMonthPreview();
            }
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', function () {
            if (viewMode === 'week') {
                currentWeekMonday = addDays(currentWeekMonday, 7);
                renderWeekView();
            } else {
                viewMonth++;
                if (viewMonth > 11) {
                    viewMonth = 0;
                    viewYear++;
                }
                renderMonthPreview();
            }
        });
    }

    if (todayBtn) {
        todayBtn.addEventListener('click', function () {
            const now = new Date();
            if (viewMode === 'week') {
                currentWeekMonday = getMonday(now);
                selectedDateStr = toDateStr(now);
                renderWeekView();
            } else {
                viewYear = now.getFullYear();
                viewMonth = now.getMonth();
                renderMonthPreview();
            }
        });
    }

    setViewMode('week');
});
</script>
@endpush
@endsection