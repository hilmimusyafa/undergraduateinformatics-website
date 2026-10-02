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

                        {{-- KALENDER INTERAKTIF AGENDA PERTEMUAN (SLOT FIX & PREVIEW BULANAN MINIMALIS) --}}
                        <div id="reservationCalendarSection" class="mt-3">
                            <div class="reservation-calendar">
                                <div class="calendar-topbar">
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        {{-- Dropdown Month Picker / Preview (tanpa ganti mode, mingguan tetap utama) --}}
                                        <div class="dropdown">
                                            <button type="button" class="calendar-month-picker-btn dropdown-toggle" id="calMonthPickerBtn" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false" title="Pilih bulan / minggu tertentu">
                                                <i class="fa-solid fa-calendar-days text-danger me-2"></i>
                                                <span id="calMonthTitle">Oktober 2026</span>
                                            </button>
                                            <div class="dropdown-menu month-preview-dropdown shadow-lg p-3" id="monthPreviewDropdown" aria-labelledby="calMonthPickerBtn" style="min-width: 290px;">
                                                <div class="d-flex align-items-center justify-content-between mb-2">
                                                    <button type="button" class="btn btn-sm btn-light border py-0 px-2" id="popoverPrevMonthBtn" title="Bulan Sebelumnya">
                                                        <i class="fa-solid fa-chevron-left fa-xs"></i>
                                                    </button>
                                                    <span class="fw-bold small text-dark" id="popoverMonthYearTitle">Oktober 2026</span>
                                                    <button type="button" class="btn btn-sm btn-light border py-0 px-2" id="popoverNextMonthBtn" title="Bulan Berikutnya">
                                                        <i class="fa-solid fa-chevron-right fa-xs"></i>
                                                    </button>
                                                </div>
                                                <div class="month-preview-grid-header mb-1">
                                                    <span>Sen</span><span>Sel</span><span>Rab</span><span>Kam</span><span>Jum</span><span>Sab</span><span>Min</span>
                                                </div>
                                                <div class="month-preview-grid" id="monthPreviewGridBody">
                                                    {{-- Mini month preview with dots di-render oleh script --}}
                                                </div>
                                                <div class="mt-2 pt-2 border-top d-flex align-items-center justify-content-between small text-muted" style="font-size: 0.72rem;">
                                                    <span><span class="month-preview-dot d-inline-block me-1" style="vertical-align:middle;"></span> Titik merah = Ada agenda</span>
                                                    <button type="button" class="btn btn-link btn-sm text-danger p-0 text-decoration-none" id="popoverTodayBtn" style="font-size: 0.72rem;">Hari Ini</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-1">
                                        <button type="button" class="calendar-nav-btn" id="calPrevBtn" title="Minggu Sebelumnya">
                                            <i class="fa-solid fa-chevron-left me-1"></i> Minggu Lalu
                                        </button>
                                        <button type="button" class="calendar-nav-btn" id="calTodayBtn">
                                            Minggu Ini
                                        </button>
                                        <button type="button" class="calendar-nav-btn" id="calNextBtn" title="Minggu Berikutnya">
                                            Minggu Depan <i class="fa-solid fa-chevron-right ms-1"></i>
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
                                        <strong>Tersedia (Bisa Diisi)</strong>
                                    </span>
                                    <span class="calendar-legend-item" id="legendOffItem">
                                        <span class="calendar-legend-dot calendar-legend-dot--off"></span>
                                        <span class="text-muted">Non-Layanan</span>
                                    </span>
                                    <span class="calendar-legend-item d-none d-md-inline-flex ms-auto text-muted small">
                                        <i class="fa-solid fa-clock me-1 text-danger"></i> Sesi Tetap: 09:00 WIB | 13:00 WIB | 15:00 WIB
                                    </span>
                                </div>

                                {{-- KALENDER MINGGUAN (DEFAULT & TETAP TAMPIL DENGAN FIXED SLOT) --}}
                                <div id="calendarWeekContainer">
                                    <div class="calendar-grid-wrapper">
                                        <div class="calendar-week-grid" id="calendarGridBody">
                                            {{-- 7 kolom hari dengan slot tetap 09:00, 13:00, 15:00 dirender oleh script --}}
                                        </div>
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
                                <div class="col-12">
                                    <div class="agenda-detail-box bg-light border p-3 rounded-3">
                                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; min-width: 44px;">
                                                    <i class="fa-solid fa-file-contract fs-5"></i>
                                                </div>
                                                <div>
                                                    <span class="text-muted small d-block mb-1"><i class="fa-solid fa-file-signature me-1 text-danger"></i> Surat Berita Acara</span>
                                                    <strong class="text-dark d-block">Dokumen Surat Berita Acara Pertemuan</strong>
                                                    <small class="text-muted">Dokumentasi resmi hasil pelaksanaan pertemuan dengan Program Studi.</small>
                                                </div>
                                            </div>
                                            <a href="{{ $schedule->document_link ?: route('reservation.berita-acara', $schedule->id) }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-danger px-3 py-2 fw-semibold">
                                                <i class="fa-solid fa-file-pdf me-1"></i> Akses Surat Berita Acara
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-light d-flex justify-content-between">
                            <a href="{{ $schedule->document_link ?: route('reservation.berita-acara', $schedule->id) }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-danger btn-sm">
                                <i class="fa-solid fa-file-pdf me-1"></i> Surat Berita Acara
                            </a>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                                <a href="#reservation-form-block" class="btn btn-danger btn-sm" data-bs-dismiss="modal">
                                    <i class="fa-solid fa-calendar-plus me-1"></i> Ajukan Jadwal di Sesi Lain
                                </a>
                            </div>
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

    let currentWeekMonday = getMonday(currentDate);
    let popoverYear = currentWeekMonday.getFullYear();
    let popoverMonth = currentWeekMonday.getMonth();
    let selectedDateStr = schedules.length > 0 ? schedules[0].date : toDateStr(currentDate);

    const monthTitleEl = document.getElementById('calMonthTitle');
    const gridBodyEl = document.getElementById('calendarGridBody');
    const monthPickerBtn = document.getElementById('calMonthPickerBtn');
    const popoverMonthYearTitle = document.getElementById('popoverMonthYearTitle');
    const popoverPrevMonthBtn = document.getElementById('popoverPrevMonthBtn');
    const popoverNextMonthBtn = document.getElementById('popoverNextMonthBtn');
    const popoverTodayBtn = document.getElementById('popoverTodayBtn');
    const monthPreviewGridEl = document.getElementById('monthPreviewGridBody');
    const prevBtn = document.getElementById('calPrevBtn');
    const nextBtn = document.getElementById('calNextBtn');
    const todayBtn = document.getElementById('calTodayBtn');

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

            const dayCol = document.createElement('div');
            let colClasses = ['calendar-day-col'];
            if (isToday) colClasses.push('calendar-day-col--today');
            if (isSelected) colClasses.push('calendar-day-col--selected');
            if (!isOperational) colClasses.push('calendar-day-col--non-operational');

            dayCol.className = colClasses.join(' ');
            dayCol.setAttribute('data-date', dateStr);

            let slotsHtml = '';
            if (isOperational) {
                allowedShifts.forEach(shift => {
                    const bookedEvent = dayEvents.find(s => s.shift === shift || s.shift.startsWith(shift));
                    if (bookedEvent) {
                        slotsHtml += `
                            <div class="calendar-slot calendar-slot--booked" data-schedule-id="${bookedEvent.id}" title="${bookedEvent.agenda || 'Pertemuan'} (${shift} WIB)">
                                <div class="calendar-slot__top">
                                    <span class="calendar-slot__time">${shift} WIB</span>
                                    <span class="calendar-slot__badge-booked">Terjadwal</span>
                                </div>
                                <div class="calendar-slot__agenda">${shortenText(bookedEvent.agenda, 15)}</div>
                                <div class="calendar-slot__meta"><i class="fa-solid fa-location-dot me-1"></i>${shortenText(bookedEvent.meeting_room || 'Ruang Prodi', 13)}</div>
                            </div>
                        `;
                    } else {
                        slotsHtml += `
                            <div class="calendar-slot calendar-slot--available" data-date="${dateStr}" data-shift="${shift}" title="Sesi ${shift} WIB Tersedia (Bisa Diisi)">
                                <div class="calendar-slot__top">
                                    <span class="calendar-slot__time">${shift} WIB</span>
                                    <span class="calendar-slot__badge-avail">Tersedia</span>
                                </div>
                                <div class="calendar-slot__status-text"><i class="fa-solid fa-circle-check"></i> Bisa Diisi</div>
                            </div>
                        `;
                    }
                });
            } else {
                slotsHtml = `
                    <div class="calendar-slot calendar-slot--off">
                        <i class="fa-solid fa-ban text-muted mb-1" style="font-size: 1.1rem;"></i>
                        <span class="fw-bold" style="font-size: 0.74rem;">Non-Layanan</span>
                    </div>
                `;
            }

            dayCol.innerHTML = `
                <div class="calendar-day-header">
                    <span class="calendar-day-header__name">${dayNames[dayOfWeek]}</span>
                    <span class="calendar-day-header__number">${dayNum}</span>
                </div>
                <div class="calendar-day-slots">
                    ${slotsHtml}
                </div>
            `;

            dayCol.addEventListener('click', function (e) {
                const bookedSlot = e.target.closest('.calendar-slot--booked');
                if (bookedSlot) {
                    const schedId = bookedSlot.getAttribute('data-schedule-id');
                    openAgendaModal(schedId);
                } else {
                    const availSlot = e.target.closest('.calendar-slot--available');
                    if (availSlot) {
                        const formEl = document.getElementById('reservation-form-block');
                        if (formEl) {
                            formEl.scrollIntoView({ behavior: 'smooth' });
                        }
                    }
                }
                selectDate(dateStr);
            });

            gridBodyEl.appendChild(dayCol);
        }

        if (!weekDates.includes(selectedDateStr)) {
            const dayWithEvent = weekDates.find(d => schedules.some(s => s.date === d));
            const targetDateStr = dayWithEvent || weekDates[0];
            selectDate(targetDateStr);
        } else {
            selectDate(selectedDateStr);
        }

        renderMonthPreviewDropdown();
    }

    function renderMonthPreviewDropdown() {
        if (!monthPreviewGridEl || !popoverMonthYearTitle) return;

        popoverMonthYearTitle.textContent = monthNames[popoverMonth] + ' ' + popoverYear;
        monthPreviewGridEl.innerHTML = '';

        const firstDayIndex = new Date(popoverYear, popoverMonth, 1).getDay();
        const mondayStartIndex = (firstDayIndex + 6) % 7;

        const totalDaysInMonth = new Date(popoverYear, popoverMonth + 1, 0).getDate();
        const totalDaysPrevMonth = new Date(popoverYear, popoverMonth, 0).getDate();

        const today = new Date();
        const todayStr = toDateStr(today);

        const currentWeekEnd = addDays(currentWeekMonday, 6);
        const currentWeekMondayStr = toDateStr(currentWeekMonday);
        const currentWeekSundayStr = toDateStr(currentWeekEnd);

        // Previous month filler days
        for (let i = mondayStartIndex - 1; i >= 0; i--) {
            const prevDayNum = totalDaysPrevMonth - i;
            const prevMonthDate = new Date(popoverYear, popoverMonth - 1, prevDayNum);
            const prevDateStr = toDateStr(prevMonthDate);

            const cell = document.createElement('div');
            cell.className = 'month-preview-cell month-preview-cell--muted';
            cell.title = 'Buka minggu ' + prevDateStr;
            cell.innerHTML = `<span>${prevDayNum}</span>`;
            cell.addEventListener('click', function (e) {
                e.stopPropagation();
                currentWeekMonday = getMonday(prevMonthDate);
                selectedDateStr = prevDateStr;
                popoverYear = currentWeekMonday.getFullYear();
                popoverMonth = currentWeekMonday.getMonth();
                renderWeekView();
                closeMonthDropdown();
            });
            monthPreviewGridEl.appendChild(cell);
        }

        // Current month days
        for (let day = 1; day <= totalDaysInMonth; day++) {
            const dateObj = new Date(popoverYear, popoverMonth, day);
            const dateStr = toDateStr(dateObj);
            const dayOfWeek = dateObj.getDay();
            const isToday = (dateStr === todayStr);
            const isSelected = (dateStr === selectedDateStr);
            const isCurrentWeek = (dateStr >= currentWeekMondayStr && dateStr <= currentWeekSundayStr);

            const dayEvents = schedules.filter(s => s.date === dateStr);

            const cell = document.createElement('div');
            let cellClasses = ['month-preview-cell'];
            if (isToday) cellClasses.push('month-preview-cell--today');
            if (isSelected) cellClasses.push('month-preview-cell--selected');
            if (isCurrentWeek) cellClasses.push('month-preview-cell--active-week');

            cell.className = cellClasses.join(' ');
            cell.setAttribute('data-date', dateStr);
            cell.title = dayNames[dayOfWeek] + ', ' + day + ' ' + monthNames[popoverMonth] + (dayEvents.length ? ' (' + dayEvents.length + ' agenda)' : '');

            let cellContent = `<span>${day}</span>`;
            if (dayEvents.length > 0) {
                cellContent += `<span class="month-preview-dot"></span>`;
            }
            cell.innerHTML = cellContent;

            cell.addEventListener('click', function (e) {
                e.stopPropagation();
                currentWeekMonday = getMonday(dateObj);
                selectedDateStr = dateStr;
                renderWeekView();
                closeMonthDropdown();
            });

            monthPreviewGridEl.appendChild(cell);
        }

        // Next month filler days
        const totalRendered = mondayStartIndex + totalDaysInMonth;
        const remainingCells = (7 - (totalRendered % 7)) % 7;
        for (let nextDay = 1; nextDay <= remainingCells; nextDay++) {
            const nextMonthDate = new Date(popoverYear, popoverMonth + 1, nextDay);
            const nextDateStr = toDateStr(nextMonthDate);

            const cell = document.createElement('div');
            cell.className = 'month-preview-cell month-preview-cell--muted';
            cell.title = 'Buka minggu ' + nextDateStr;
            cell.innerHTML = `<span>${nextDay}</span>`;
            cell.addEventListener('click', function (e) {
                e.stopPropagation();
                currentWeekMonday = getMonday(nextMonthDate);
                selectedDateStr = nextDateStr;
                popoverYear = currentWeekMonday.getFullYear();
                popoverMonth = currentWeekMonday.getMonth();
                renderWeekView();
                closeMonthDropdown();
            });
            monthPreviewGridEl.appendChild(cell);
        }
    }

    function closeMonthDropdown() {
        if (monthPickerBtn && typeof bootstrap !== 'undefined' && bootstrap.Dropdown) {
            const dropdownInstance = bootstrap.Dropdown.getInstance(monthPickerBtn);
            if (dropdownInstance) {
                dropdownInstance.hide();
            }
        }
    }

    function selectDate(dateStr) {
        selectedDateStr = dateStr;

        document.querySelectorAll('.calendar-day-col').forEach(c => {
            if (c.getAttribute('data-date') === dateStr) {
                c.classList.add('calendar-day-col--selected');
            } else {
                c.classList.remove('calendar-day-col--selected');
            }
        });
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
            currentWeekMonday = addDays(currentWeekMonday, -7);
            popoverYear = currentWeekMonday.getFullYear();
            popoverMonth = currentWeekMonday.getMonth();
            renderWeekView();
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', function () {
            currentWeekMonday = addDays(currentWeekMonday, 7);
            popoverYear = currentWeekMonday.getFullYear();
            popoverMonth = currentWeekMonday.getMonth();
            renderWeekView();
        });
    }

    if (todayBtn) {
        todayBtn.addEventListener('click', function () {
            const now = new Date();
            currentWeekMonday = getMonday(now);
            selectedDateStr = toDateStr(now);
            popoverYear = now.getFullYear();
            popoverMonth = now.getMonth();
            renderWeekView();
        });
    }

    if (popoverPrevMonthBtn) {
        popoverPrevMonthBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            popoverMonth--;
            if (popoverMonth < 0) {
                popoverMonth = 11;
                popoverYear--;
            }
            renderMonthPreviewDropdown();
        });
    }

    if (popoverNextMonthBtn) {
        popoverNextMonthBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            popoverMonth++;
            if (popoverMonth > 11) {
                popoverMonth = 0;
                popoverYear++;
            }
            renderMonthPreviewDropdown();
        });
    }

    if (popoverTodayBtn) {
        popoverTodayBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            const now = new Date();
            currentWeekMonday = getMonday(now);
            selectedDateStr = toDateStr(now);
            popoverYear = now.getFullYear();
            popoverMonth = now.getMonth();
            renderWeekView();
            closeMonthDropdown();
        });
    }

    if (monthPickerBtn) {
        monthPickerBtn.addEventListener('show.bs.dropdown', function () {
            popoverYear = currentWeekMonday.getFullYear();
            popoverMonth = currentWeekMonday.getMonth();
            renderMonthPreviewDropdown();
        });
    }

    renderWeekView();
});
</script>
@endpush
@endsection