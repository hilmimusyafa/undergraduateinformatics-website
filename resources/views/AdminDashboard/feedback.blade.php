@extends('layouts.adminlayout')

@section('title', 'Manajemen Form Link')

@section('content')
    <div class="admin modern-page">
        <h2 class="modern-page__heading">Manajemen Form Link</h2>
        @include('partials.Alerts')
        <section class="modern-card">
            <form method="POST" action="{{ route('admin.form-link.feedback.update') }}">
                @csrf
                @method('PUT')
                <label for="feedback_link" class="form-label">URL tautan feedback (Google Forms, Microsoft Forms, dan lainnya)</label>
                <div class="feedback-form-row">
                    <input id="feedback_link" name="feedback_link" type="url" class="form-control @error('feedback_link') is-invalid @enderror"
                        value="{{ old('feedback_link', $feedbackLink?->link) }}" placeholder="https://forms.office.com/..." required>
                    <button class="modern-button modern-button--primary" type="submit"><i class="fa-solid fa-circle-check"></i> Simpan</button>
                </div>
                @error('feedback_link')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </form>
        </section>

        <aside class="feature-note">
            <i class="fa-solid fa-comment-dots"></i>
            <div>
                <h3>Informasi fitur</h3>
                <p>Tautan yang disimpan akan digunakan pada halaman publik Feedback. Pastikan URL dapat diakses oleh publik.</p>
                @if ($feedbackLink?->link)
                    <a href="{{ $feedbackLink->link }}" target="_blank" rel="noopener noreferrer">Uji tautan saat ini <i class="fa-solid fa-arrow-up-right-from-square"></i></a>
                @endif
            </div>
        </aside>

        <section class="modern-card">
            <form method="POST" action="{{ route('admin.form-link.reservation.update') }}">
                @csrf
                @method('PUT')
                <label for="reservation_link" class="form-label">URL tautan reservasi (Microsoft Forms)</label>
                <div class="feedback-form-row">
                    <input id="reservation_link" name="reservation_link" type="url" class="form-control @error('reservation_link') is-invalid @enderror"
                        value="{{ old('reservation_link', $reservationLink?->link) }}" placeholder="https://forms.office.com/..." required>
                    <button class="modern-button modern-button--primary" type="submit"><i class="fa-solid fa-circle-check"></i> Simpan</button>
                </div>
                @error('reservation_link')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </form>
        </section>

        <aside class="feature-note">
            <i class="fa-solid fa-calendar-check"></i>
            <div>
                <h3>Informasi fitur</h3>
                <p>Tautan yang disimpan akan digunakan pada halaman publik Reservasi. Pastikan URL Microsoft Forms dapat diakses oleh publik.</p>
                @if ($reservationLink?->link)
                    <a href="{{ $reservationLink->link }}" target="_blank" rel="noopener noreferrer">Uji tautan saat ini <i class="fa-solid fa-arrow-up-right-from-square"></i></a>
                @endif
            </div>
        </aside>
    </div>
@endsection
