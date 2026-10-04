@extends('layouts.adminlayout')

@section('title', 'Upload Data Statistik Mahasiswa')

@section('content')
    <div class="admin modern-page">
        <div class="dashboard-heading">
            <h2 class="modern-page__heading">Upload Data Statistik Mahasiswa</h2>
            <div class="dashboard-heading__actions">
                <a href="{{ route('admin.dashboard.template') }}" download="template-statistik-mahasiswa.xlsx" class="modern-button modern-button--soft" style="text-decoration: none;">
                    <i class="fa-solid fa-file-excel text-success"></i> Unduh File Template (.xlsx)
                </a>
            </div>
        </div>

        <div id="upload-alert" class="d-none" role="alert"></div>

        <div class="modern-card p-3" style="background: #fafaf9; border-left: 4px solid #16a34a;">
            <div class="d-flex align-items-start gap-3">
                <div style="font-size: 1.5rem; color: #16a34a; line-height: 1; margin-top: 2px;">
                    <i class="fa-solid fa-circle-info"></i>
                </div>
                <div class="flex-grow-1" style="font-size: 0.88rem; color: #374151;">
                    <strong class="d-block mb-1 text-dark" style="font-size: 0.92rem;">Panduan Format File Excel (Template):</strong>
                    <ul class="mb-2 ps-3" style="line-height: 1.5;">
                        <li>Setiap <strong>Sheet</strong> pada file Excel akan otomatis menjadi <strong>1 grafik/chart</strong>.</li>
                        <li><strong>Baris 1 (Header):</strong> Kolom A untuk Label/Kategori, Kolom B untuk Jumlah/Nilai.</li>
                        <li><strong>Baris 2 dst (Data):</strong> Kolom A berisi nama teks, Kolom B harus berupa <strong>angka/nilai numerik</strong>.</li>
                    </ul>
                    <div>
                        Format belum sesuai? 
                        <a href="{{ route('admin.dashboard.template') }}" download="template-statistik-mahasiswa.xlsx" class="fw-bold text-decoration-underline" style="color: #16a34a;">
                            <i class="fa-solid fa-download me-1"></i>Unduh contoh file template (example.xlsx) di sini
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <section class="modern-card">
            <div class="upload-dropzone">
                <p class="mb-1 fw-semibold" style="font-size: 0.95rem; color: #1f2937;">Pilih file Excel (.xlsx, .xls) untuk diunggah</p>
                <p class="text-muted mb-3" style="font-size: 0.82rem;">Pastikan struktur tabel mengikuti template contoh agar berhasil diproses.</p>
                <input id="excel_file" class="form-control" type="file" accept=".xlsx,.xls">
            </div>
        </section>

        <section id="preview" class="d-none">
            <div id="preview-items" class="ds-editor-list"></div>
            <div class="mt-4">
                <button id="save-button" class="modern-button modern-button--primary" type="button" disabled>
                    <i class="fa-solid fa-circle-check"></i> Konfirmasi &amp; Simpan
                </button>
            </div>
        </section>

        <div class="d-flex gap-2">
            <button id="clear-button" class="modern-button modern-button--soft" type="button"><i class="fa-solid fa-trash"></i> Hapus Semua Data Grafik</button>
            <a href="{{ route('admin.dashboard') }}" class="modern-button modern-button--soft">Batal</a>
        </div>

        <div class="modal fade" id="saveConfirmModal" tabindex="-1" aria-labelledby="saveConfirmModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="saveConfirmModalLabel">Konfirmasi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Simpan data ini? Dataset Statistik Mahasiswa yang sudah ada akan digantikan.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="modern-button modern-button--soft" data-bs-dismiss="modal">Batal</button>
                        <button type="button" id="save-confirm-btn" class="modern-button modern-button--primary">Simpan</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="clearConfirmModal" tabindex="-1" aria-labelledby="clearConfirmModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="clearConfirmModalLabel">Konfirmasi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Hapus semua data grafik di Statistik Mahasiswa?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="modern-button modern-button--soft" data-bs-dismiss="modal">Batal</button>
                        <button type="button" id="clear-confirm-btn" class="modern-button modern-button--primary">Hapus</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@include('AdminDashboard._chart-assets')

@push('scripts')
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
        const alertBox = document.getElementById('upload-alert');
        const fileInput = document.getElementById('excel_file');
        const saveButton = document.getElementById('save-button');
        const preview = document.getElementById('preview');
        const previewItems = document.getElementById('preview-items');
        let parsedDatasets = [];

        function notify(message, type = 'success') {
            const noticeType = type === 'danger' ? 'danger' : type === 'warning' ? 'info' : 'success';
            let icon = 'fa-circle-check';
            if (noticeType === 'danger') icon = 'fa-circle-exclamation';
            else if (noticeType === 'info') icon = 'fa-circle-info';

            alertBox.innerHTML = `
                <div class="d-flex align-items-start gap-2">
                    <i class="fa-solid ${icon} mt-1"></i>
                    <div style="flex: 1; line-height: 1.45;">${escapeHtml(message)}</div>
                </div>`;
            alertBox.className = `modern-notice modern-notice--${noticeType}`;
            alertBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }

        function setBusy(button, isBusy, label) {
            button.disabled = isBusy;
            button.dataset.label ??= button.innerHTML;
            button.innerHTML = isBusy ? '<i class="fa-solid fa-spinner fa-spin"></i> ' + label : button.dataset.label;
        }

        function escapeHtml(value) {
            const element = document.createElement('span');
            element.textContent = value ?? '';
            return element.innerHTML;
        }

        function readEditorDataset(card) {
            return {
                title: card.querySelector('.ds-editor__title').value.trim() || 'Tanpa judul',
                chart_type: card.querySelector('.ds-editor__type').value,
                items: Array.from(card.querySelectorAll('.ds-row')).map((row) => ({
                    label: row.querySelector('.ds-row__label').value.trim(),
                    value: Number(row.querySelector('.ds-row__value').value),
                })).filter((item) => item.label !== '' && !Number.isNaN(item.value)),
            };
        }

        function renderEditorChart(card) {
            const canvas = card.querySelector('canvas[data-chart]');
            const dataset = readEditorDataset(card);
            window.renderChartPreview(canvas, dataset.items.map((item) => item.label), dataset.items.map((item) => item.value), dataset.chart_type);
        }

        function datasetEditorHTML(dataset, uid) {
            const typeOptions = ['bar', 'line', 'pie'].map((type) =>
                `<option value="${type}" ${dataset.chart_type === type ? 'selected' : ''}>${type.charAt(0).toUpperCase() + type.slice(1)}</option>`
            ).join('');

            const rows = (dataset.items || []).map((item) => `
                <div class="ds-row">
                    <input class="ds-row__label" type="text" value="${escapeHtml(item.label)}" aria-label="Label" placeholder="Label">
                    <input class="ds-row__value" type="number" step="any" value="${escapeHtml(item.value)}" aria-label="Nilai" placeholder="0">
                    <button type="button" class="ds-row__remove" title="Hapus baris" aria-label="Hapus baris"><i class="fa-solid fa-xmark"></i></button>
                </div>`).join('');

            return `
                <article class="ds-editor" data-uid="${uid}">
                    <header class="ds-editor__head">
                        <input class="ds-editor__title" type="text" value="${escapeHtml(dataset.title)}" placeholder="Judul chart" aria-label="Judul chart">
                        <select class="ds-editor__type" aria-label="Tipe grafik">${typeOptions}</select>
                    </header>
                    <div class="ds-editor__body">
                        <div class="ds-editor__preview"><canvas data-chart></canvas></div>
                        <div class="ds-editor__values">
                            ${rows}
                            <button type="button" class="ds-editor__add"><i class="fa-solid fa-plus"></i> Tambah data</button>
                        </div>
                    </div>
                </article>`;
        }

        function renderEditorList(container, datasetList) {
            container.replaceChildren();
            datasetList.forEach((dataset, index) => {
                container.insertAdjacentHTML('beforeend', datasetEditorHTML(dataset, `d${index}`));
            });
            container.querySelectorAll('.ds-editor').forEach((card) => renderEditorChart(card));
        }

        function bindEditorList(container) {
            container.addEventListener('input', (event) => {
                const card = event.target.closest('.ds-editor');
                if (card) renderEditorChart(card);
            });

            container.addEventListener('click', (event) => {
                const card = event.target.closest('.ds-editor');
                if (!card) return;

                if (event.target.closest('.ds-row__remove')) {
                    event.target.closest('.ds-row').remove();
                    renderEditorChart(card);
                    return;
                }

                if (event.target.closest('.ds-editor__add')) {
                    const values = card.querySelector('.ds-editor__values');
                    const row = document.createElement('div');
                    row.className = 'ds-row';
                    row.innerHTML = `
                        <input class="ds-row__label" type="text" value="" aria-label="Label" placeholder="Label">
                        <input class="ds-row__value" type="number" step="any" value="" aria-label="Nilai" placeholder="0">
                        <button type="button" class="ds-row__remove" title="Hapus baris" aria-label="Hapus baris"><i class="fa-solid fa-xmark"></i></button>`;
                    values.insertBefore(row, values.querySelector('.ds-editor__add'));
                    renderEditorChart(card);
                }
            });
        }

        bindEditorList(previewItems);

        function requestOptions(method, body) {
            const isForm = body instanceof FormData;
            return {
                method,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    ...(isForm ? {} : { 'Content-Type': 'application/json' }),
                },
                body,
            };
        }

        fileInput.addEventListener('change', async () => {
            if (!fileInput.files.length) {
                return;
            }

            fileInput.disabled = true;
            notify('Memproses file Excel...', 'info');
            try {
                const data = new FormData();
                data.append('excel_file', fileInput.files[0]);

                let response;
                try {
                    response = await fetch('{{ route('admin.dashboard.extract') }}', requestOptions('POST', data));
                } catch (fetchErr) {
                    throw new Error('Gagal mengunggah file. Pastikan format tabel sesuai dengan contoh template (.xlsx) dan file tidak rusak.');
                }

                let result = null;
                try {
                    result = await response.json();
                } catch (_) {
                    result = null;
                }

                if (!response.ok || !result || !result.success) {
                    if (response.status === 419) {
                        throw new Error('Sesi telah kedaluwarsa. Silakan refresh halaman dan coba unggah kembali.');
                    }
                    if (response.status === 413) {
                        throw new Error('Ukuran file terlalu besar. Maksimal ukuran file adalah 10 MB.');
                    }
                    const serverMessage = result?.message || (result?.errors ? Object.values(result.errors).flat().join(' ') : null);
                    throw new Error(serverMessage || 'Format isi file Excel tidak sesuai dengan template. Pastikan setiap sheet memiliki kolom label dan nilai angka seperti pada contoh template.');
                }

                if (!result.datasets || !result.datasets.length) {
                    throw new Error('Format isi file Excel tidak sesuai dengan template atau data kosong. Pastikan kolom pertama berisi kategori dan kolom kedua berisi angka nilai numerik.');
                }

                parsedDatasets = result.datasets;
                renderEditorList(previewItems, parsedDatasets);
                const countEl = document.getElementById('preview-count');
                if (countEl) countEl.textContent = `${result.datasets.length} dataset siap disimpan`;
                preview.classList.remove('d-none');
                saveButton.disabled = false;
                notify('Data berhasil diparse dari file Excel. Sesuaikan jika perlu, lalu klik Konfirmasi & Simpan.');
            } catch (error) {
                parsedDatasets = [];
                saveButton.disabled = true;
                preview.classList.add('d-none');

                let errMsg = error?.message || '';
                if (!errMsg || errMsg === 'Failed to fetch' || errMsg.includes('NetworkError') || errMsg.includes('Load failed')) {
                    errMsg = 'Gagal memproses file Excel. Format tabel mungkin tidak sesuai template atau terjadi kendala koneksi. Silakan unduh dan gunakan contoh template file Excel yang disediakan.';
                }
                notify(errMsg, 'danger');
            } finally {
                fileInput.disabled = false;
            }
        });

        saveButton.addEventListener('click', () => {
            const collected = Array.from(previewItems.querySelectorAll('.ds-editor')).map(readEditorDataset);
            if (!collected.length) {
                notify('Tidak ada dataset yang bisa disimpan.', 'danger');
                return;
            }
            bootstrap.Modal.getOrCreateInstance(document.getElementById('saveConfirmModal')).show();
        });

        document.getElementById('save-confirm-btn').addEventListener('click', async () => {
            bootstrap.Modal.getOrCreateInstance(document.getElementById('saveConfirmModal')).hide();
            const collected = Array.from(previewItems.querySelectorAll('.ds-editor')).map(readEditorDataset);
            if (!collected.length) {
                notify('Tidak ada dataset yang bisa disimpan.', 'danger');
                return;
            }

            setBusy(saveButton, true, 'Menyimpan...');
            try {
                const response = await fetch('{{ route('admin.dashboard.save') }}', requestOptions('POST', JSON.stringify({ datasets: collected })));
                const result = await response.json();
                if (!response.ok || !result.success) throw new Error(result.message || 'Data gagal disimpan.');
                notify('Data Statistik Mahasiswa berhasil dipublikasikan.', 'success');
                window.setTimeout(() => window.location.assign('{{ route('admin.dashboard') }}'), 600);
            } catch (error) {
                setBusy(saveButton, false);
                notify(error.message, 'danger');
            }
        });

        document.getElementById('clear-button').addEventListener('click', () => {
            bootstrap.Modal.getOrCreateInstance(document.getElementById('clearConfirmModal')).show();
        });

        document.getElementById('clear-confirm-btn').addEventListener('click', async () => {
            bootstrap.Modal.getOrCreateInstance(document.getElementById('clearConfirmModal')).hide();
            try {
                const response = await fetch('{{ route('admin.dashboard.cleardata') }}', requestOptions('DELETE'));
                const result = await response.json();
                if (!response.ok || !result.success) throw new Error(result.message || 'Data gagal dihapus.');
                notify('Semua data grafik berhasil dihapus.');
            } catch (error) {
                notify(error.message, 'danger');
            }
        });
    </script>
@endpush