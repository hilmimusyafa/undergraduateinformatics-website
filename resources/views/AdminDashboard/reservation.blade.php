@extends('layouts.adminlayout')

@section('title', 'Approval Reservasi')

@section('content')
    <div class="admin modern-page">
        <div class="reservation-heading">
            <div>
                <h2 class="modern-page__heading">Approval Reservasi</h2>
            </div>
            @if ($reservationTableReady && $reservationDetailsReady)
                <div class="reservation-heading__actions">
                    <span class="reservation-count"><i class="fa-regular fa-calendar"></i> {{ $reservations->count() }} reservasi</span>
                    <button class="modern-button modern-button--primary" type="button" data-bs-toggle="modal" data-bs-target="#reservation-create"><i class="fa-solid fa-plus"></i> Tambah Reservasi</button>
                </div>
            @endif
        </div>

        <div id="reservation-alert" class="d-none" role="alert"></div>

        @if (! $reservationTableReady)
            <section class="empty-state modern-card">
                <i class="fa-solid fa-database"></i>
                <p>Database reservasi belum siap</p>
                <p>Tabel <code>reservation_schedules</code> belum tersedia. Jalankan migration reservasi agar pengajuan dapat ditampilkan.</p>
            </section>
        @elseif (! $reservationDetailsReady)
            <section class="empty-state modern-card">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <p>Struktur reservasi belum lengkap</p>
                <p>Kolom detail reservasi belum tersedia. Jalankan migration detail reservasi terlebih dahulu.</p>
            </section>
        @elseif ($reservations->isEmpty())
            <section class="empty-state modern-card">
                <i class="fa-regular fa-calendar-xmark"></i>
                <p>Belum ada pengajuan reservasi</p>
                <p>Pengajuan jadwal baru dari formulir reservasi akan muncul di halaman ini.</p>
            </section>
        @else
            <section class="table-admin">
                <div class="table-responsive">
                    <table class="reservation-table">
                        <thead>
                            <tr><th>Tanggal</th><th>Sesi</th><th>Diajukan oleh</th><th>Ruangan</th><th>Berita acara</th><th class="text-end">Aksi</th></tr>
                        </thead>
                        <tbody>
                            @foreach ($reservations as $reservation)
                                <tr id="reservation-{{ $reservation->id }}">
                                    <td><strong>{{ \Illuminate\Support\Carbon::parse($reservation->date)->translatedFormat('d M Y') }}</strong><br><small class="text-muted">{{ \Illuminate\Support\Carbon::parse($reservation->date)->translatedFormat('l') }}</small></td>
                                    <td><span class="shift-pill"><i class="fa-regular fa-clock"></i> {{ substr($reservation->shift, 0, 5) }} WIB</span></td>
                                    <td><strong>{{ $reservation->requested_by }}</strong>@if ($reservation->study_program)<br><small class="text-muted">{{ $reservation->study_program }}</small>@endif</td>
                                    <td>{{ $reservation->meeting_room ?: '—' }}</td>
                                    <td>@if ($reservation->document_link)<a class="reservation-document" href="{{ $reservation->document_link }}" target="_blank" rel="noopener noreferrer"><i class="fa-regular fa-file-pdf"></i> Lihat PDF</a>@else<span class="text-muted">Belum tersedia</span>@endif</td>
                                    <td class="text-end text-nowrap">
                                        <button class="modern-button modern-button--soft" type="button" title="Lihat detail" data-bs-toggle="modal" data-bs-target="#reservation-detail-{{ $reservation->id }}"><i class="fa-regular fa-eye"></i></button>
                                        <button class="modern-button modern-button--soft" type="button" title="Ubah reservasi" data-bs-toggle="modal" data-bs-target="#reservation-edit-{{ $reservation->id }}"><i class="fa-solid fa-pen"></i></button>
                                        <button class="modern-button modern-button--danger delete-reservation" type="button" title="Hapus reservasi" data-id="{{ $reservation->id }}"><i class="fa-solid fa-trash"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

            @foreach ($reservations as $reservation)
                <div class="modal fade" id="reservation-detail-{{ $reservation->id }}" tabindex="-1" aria-labelledby="reservation-detail-title-{{ $reservation->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-scrollable"><div class="modal-content reservation-modal">
                        <div class="modal-header"><div><p class="modal-kicker">PENGAJUAN #{{ $reservation->id }}</p><h3 class="modal-title fs-5" id="reservation-detail-title-{{ $reservation->id }}">Detail Reservasi</h3></div><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button></div>
                        <div class="modal-body">
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
                        </div>
                        <div class="modal-footer">@if ($reservation->document_link)<a class="modern-button modern-button--primary" href="{{ $reservation->document_link }}" target="_blank" rel="noopener noreferrer"><i class="fa-regular fa-file-pdf"></i> Lihat Berita Acara</a>@endif<button type="button" class="modern-button modern-button--soft" data-bs-dismiss="modal">Tutup</button></div>
                    </div></div>
                </div>

                <div class="modal fade" id="reservation-edit-{{ $reservation->id }}" tabindex="-1" aria-labelledby="reservation-edit-title-{{ $reservation->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-scrollable"><form class="modal-content reservation-modal reservation-edit-form" data-id="{{ $reservation->id }}">
                        <div class="modal-header"><div><p class="modal-kicker">PENGAJUAN #{{ $reservation->id }}</p><h3 class="modal-title fs-5" id="reservation-edit-title-{{ $reservation->id }}">Ubah Reservasi</h3></div><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button></div>
                        <div class="modal-body">
                            <div class="reservation-form-grid">
                                <label>Tanggal<input class="form-control" name="date" type="date" value="{{ $reservation->date }}" required></label>
                                <label>Sesi<select class="form-select" name="shift" required><option value="09:00" @selected(substr($reservation->shift, 0, 5) === '09:00')>09:00 WIB</option><option value="13:00" @selected(substr($reservation->shift, 0, 5) === '13:00')>13:00 WIB</option><option value="15:00" @selected(substr($reservation->shift, 0, 5) === '15:00')>15:00 WIB</option></select></label>
                                <label>Diajukan oleh<input class="form-control" name="requested_by" value="{{ $reservation->requested_by }}" required></label>
                                <label>Ruang pertemuan<input class="form-control" name="meeting_room" value="{{ $reservation->meeting_room }}"></label>
                                <label>Program studi<input class="form-control" name="study_program" value="{{ $reservation->study_program }}"></label>
                                <label>Peserta<input class="form-control" name="participants" value="{{ $reservation->participants }}"></label>
                                <label>Kota<input class="form-control" name="city" value="{{ $reservation->city }}"></label>
                                <label>Nama penandatangan Prodi<input class="form-control" name="prodi_signature_name" value="{{ $reservation->prodi_signature_name }}"></label>
                                <label>Jabatan penandatangan Prodi<input class="form-control" name="prodi_signature_position" value="{{ $reservation->prodi_signature_position }}"></label>
                                <label>Nama penandatangan pihak terkait<input class="form-control" name="related_party_signature_name" value="{{ $reservation->related_party_signature_name }}"></label>
                                <label>Jabatan penandatangan pihak terkait<input class="form-control" name="related_party_signature_position" value="{{ $reservation->related_party_signature_position }}"></label>
                                <label class="reservation-form-grid__full">Agenda<textarea class="form-control" name="agenda">{{ $reservation->agenda }}</textarea></label>
                            </div>
                            <p class="reservation-form-hint"><i class="fa-solid fa-circle-info"></i> Menyimpan perubahan akan memperbarui data dan membuat ulang berita acara PDF.</p>
                        </div>
                        <div class="modal-footer"><button type="button" class="modern-button modern-button--soft" data-bs-dismiss="modal">Batal</button><button class="modern-button modern-button--primary" type="submit"><i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan</button></div>
                    </form></div>
                </div>
            @endforeach
        @endif

        @if ($reservationTableReady && $reservationDetailsReady)
            <div class="modal fade" id="reservation-create" tabindex="-1" aria-labelledby="reservation-create-title" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-scrollable"><form class="modal-content reservation-modal reservation-create-form">
                    <div class="modal-header"><div><p class="modal-kicker">JADWAL BARU</p><h3 class="modal-title fs-5" id="reservation-create-title">Tambah Reservasi</h3></div><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button></div>
                    <div class="modal-body">
                        <div class="modern-notice modern-notice--info mb-4"><i class="fa-solid fa-circle-info"></i> Jadwal hanya dapat dibuat untuk hari Senin, Selasa, Kamis, atau Jumat. Backend akan mencegah sesi yang sudah terisi.</div>
                        <div class="reservation-form-grid">
                            <label>Tanggal<input class="form-control" name="date" type="date" required></label>
                            <label>Sesi<select class="form-select" name="shift" required><option value="" selected disabled>Pilih sesi</option><option value="09:00">09:00 WIB</option><option value="13:00">13:00 WIB</option><option value="15:00">15:00 WIB</option></select></label>
                            <label>Diajukan oleh<input class="form-control" name="requested_by" required placeholder="Nama pemohon"></label>
                            <label>Ruang pertemuan<input class="form-control" name="meeting_room" placeholder="Contoh: Ruang Rapat 1"></label>
                            <label>Program studi<input class="form-control" name="study_program" value="S1 Informatika"></label>
                            <label>Peserta<input class="form-control" name="participants" placeholder="Contoh: 10 orang"></label>
                            <label>Kota<input class="form-control" name="city" placeholder="Contoh: Bandung"></label>
                            <label>Nama penandatangan Prodi<input class="form-control" name="prodi_signature_name"></label>
                            <label>Jabatan penandatangan Prodi<input class="form-control" name="prodi_signature_position"></label>
                            <label>Nama penandatangan pihak terkait<input class="form-control" name="related_party_signature_name"></label>
                            <label>Jabatan penandatangan pihak terkait<input class="form-control" name="related_party_signature_position"></label>
                            <label class="reservation-form-grid__full">Agenda<textarea class="form-control" name="agenda" placeholder="Jelaskan tujuan pertemuan"></textarea></label>
                        </div>
                        <p class="reservation-form-hint"><i class="fa-solid fa-file-pdf"></i> Setelah disimpan, backend akan membuat berita acara dalam format PDF secara otomatis.</p>
                    </div>
                    <div class="modal-footer"><button type="button" class="modern-button modern-button--soft" data-bs-dismiss="modal">Batal</button><button class="modern-button modern-button--primary" type="submit"><i class="fa-solid fa-calendar-plus"></i> Simpan Reservasi</button></div>
                </form></div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        const reservationAlert = document.getElementById('reservation-alert');
        const scheduleApiUrl = '{{ url('/api/reservation/schedule') }}';

        function notifyReservation(message, type) {
            reservationAlert.textContent = message;
            reservationAlert.className = `modern-notice modern-notice--${type === 'danger' ? 'danger' : 'success'}`;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        async function requestReservation(url, options) {
            const response = await fetch(url, options);
            const result = await response.json();
            if (!response.ok || result.status !== 'success') {
                const firstError = result.errors ? Object.values(result.errors).flat()[0] : null;
                throw new Error(firstError || result.message || 'Permintaan reservasi gagal diproses.');
            }
            return result;
        }

        document.querySelectorAll('.delete-reservation').forEach((button) => button.addEventListener('click', async () => {
            if (!window.confirm('Hapus reservasi ini beserta dokumen PDF-nya?')) return;
            button.disabled = true;
            try {
                await requestReservation(`${scheduleApiUrl}/${button.dataset.id}`, { method: 'DELETE' });
                document.getElementById(`reservation-${button.dataset.id}`).remove();
                notifyReservation('Reservasi dan dokumen terkait berhasil dihapus.', 'success');
            } catch (error) {
                button.disabled = false;
                notifyReservation(error.message, 'danger');
            }
        }));

        document.querySelectorAll('.reservation-edit-form').forEach((form) => form.addEventListener('submit', async (event) => {
            event.preventDefault();
            const submitButton = form.querySelector('[type="submit"]');
            submitButton.disabled = true;
            try {
                const payload = Object.fromEntries(new FormData(form).entries());
                await requestReservation(`${scheduleApiUrl}/${form.dataset.id}`, {
                    method: 'PUT', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' }, body: JSON.stringify(payload),
                });
                notifyReservation('Reservasi berhasil diperbarui. Halaman akan dimuat ulang.', 'success');
                window.setTimeout(() => window.location.reload(), 600);
            } catch (error) {
                submitButton.disabled = false;
                notifyReservation(error.message, 'danger');
            }
        }));

        document.querySelectorAll('.reservation-create-form').forEach((form) => form.addEventListener('submit', async (event) => {
            event.preventDefault();
            const submitButton = form.querySelector('[type="submit"]');
            submitButton.disabled = true;
            try {
                const payload = Object.fromEntries(new FormData(form).entries());
                await requestReservation(scheduleApiUrl, {
                    method: 'POST', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' }, body: JSON.stringify(payload),
                });
                notifyReservation('Reservasi berhasil dibuat. Halaman akan dimuat ulang.', 'success');
                window.setTimeout(() => window.location.reload(), 600);
            } catch (error) {
                submitButton.disabled = false;
                notifyReservation(error.message, 'danger');
            }
        }));
    </script>
@endpush
