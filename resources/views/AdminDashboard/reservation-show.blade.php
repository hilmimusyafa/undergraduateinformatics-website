@extends('layouts.adminlayout')

@section('title', 'Detail Reservasi')

@section('content')
    <div class="admin col-md-9">
        <div class="kembali">
            <a href="{{ route('admin.reservation') }}">
                <i class="fa-solid fa-arrow-left"></i>Kembali
            </a>
        </div>

        <div class="top">
            <h1>Detail Reservasi</h1>
        </div>

        <div class="form row form--wide">
            <div class="reservation-detail-grid">
                <div><span><i class="fa-regular fa-calendar"></i> Tanggal</span><strong>{{ \Illuminate\Support\Carbon::parse($reservation->date)->translatedFormat('l, d F Y') }}</strong></div>
                <div><span><i class="fa-regular fa-clock"></i> Sesi</span><strong>{{ substr($reservation->shift, 0, 5) }} WIB</strong></div>
                <div><span><i class="fa-regular fa-user"></i> Diajukan oleh</span><strong>{{ $reservation->requested_by }}</strong></div>
                <div><span><i class="fa-solid fa-location-dot"></i> Ruang pertemuan</span><strong>{{ $reservation->meeting_room ?: '—' }}</strong></div>
                <div><span><i class="fa-solid fa-book-open"></i> Program studi</span><strong>{{ $reservation->study_program ?: 'S1 Informatika' }}</strong></div>
                <div><span><i class="fa-solid fa-users"></i> Peserta</span><strong>{{ $reservation->participants ?: '—' }}</strong></div>
                <div><span><i class="fa-solid fa-city"></i> Kota</span><strong>{{ $reservation->city ?: '—' }}</strong></div>
                <div><span><i class="fa-regular fa-clock"></i> Diajukan pada</span><strong>{{ $reservation->created_at?->translatedFormat('d M Y, H:i') ?: '—' }}</strong></div>
            </div>
            <section class="reservation-agenda"><span>Agenda</span><p>{{ $reservation->agenda ?: 'Tidak ada agenda yang dicantumkan.' }}</p></section>
            <div class="signature-grid">
                <section class="signature-card signature-card--prodi"><span>Pihak Prodi</span><strong>{{ $reservation->prodi_signature_name ?: '—' }}</strong><small>{{ $reservation->prodi_signature_position ?: '—' }}</small></section>
                <section class="signature-card signature-card--related"><span>Pihak Terkait</span><strong>{{ $reservation->related_party_signature_name ?: $reservation->requested_by }}</strong><small>{{ $reservation->related_party_signature_position ?: '—' }}</small></section>
            </div>
            <div class="mt-4 d-flex gap-2">
                @if ($reservation->document_link)
                    <a class="modern-button modern-button--primary" href="{{ $reservation->document_link }}" target="_blank" rel="noopener noreferrer"><i class="fa-regular fa-file-pdf"></i> Lihat Berita Acara</a>
                @endif
                <a class="modern-button modern-button--soft" href="{{ route('admin.reservation.edit', ['id' => $reservation->id]) }}"><i class="fa-solid fa-pen"></i> Ubah Reservasi</a>
                <a class="modern-button modern-button--soft" href="{{ route('admin.reservation') }}">Kembali</a>
            </div>
        </div>
    </div>
@endsection