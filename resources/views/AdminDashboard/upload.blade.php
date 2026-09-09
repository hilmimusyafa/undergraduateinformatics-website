@extends('layouts.adminlayout')

@section('title', 'Upload Data Dashboard')

@section('content')
    <div class="admin modern-page">
        <div class="kembali">
            <a href="{{ route('admin.dashboard') }}">
                <i class="fa-solid fa-arrow-left"></i>Kembali
            </a>
        </div>
        <div class="top">
            <h1>Upload Data Dashboard</h1>
        </div>

        <div id="upload-alert" class="d-none" role="alert"></div>

        <section class="modern-card">
            <div class="upload-dropzone">
                <i class="fa-solid fa-cloud-arrow-up"></i>
                <p>Pilih file Excel (.xlsx, .xls) untuk diunggah</p>
                <input id="excel_file" class="form-control" type="file" accept=".xlsx,.xls">
                <button id="preview-button" class="modern-button modern-button--soft" type="button"><i class="fa-regular fa-eye"></i> Parse &amp; Preview</button>
            </div>
        </section>

        <section id="preview" class="preview-section d-none">
            <h3 class="preview-section__title"><i class="fa-regular fa-file-lines"></i> Hasil parse <span id="preview-count" class="preview-badge"></span></h3>
            <p class="preview-help">Periksa dan sesuaikan data di bawah. Judul default diambil dari nama sheet dan bisa Anda ubah. Penyimpanan akan menggantikan dataset dashboard yang sebelumnya dipublikasikan.</p>
            <div id="preview-items" class="ds-editor-list"></div>
            <div class="mt-4">
                <button id="save-button" class="modern-button modern-button--primary" type="button" disabled>
                    <i class="fa-solid fa-circle-check"></i> Konfirmasi &amp; Simpan
                </button>
            </div>
        </section>

        <div class="preview-section">
            <button id="clear-button" class="modern-button modern-button--danger" type="button"><i class="fa-solid fa-trash"></i> Hapus Semua Data Grafik</button>
        </div>
    </div>
@endsection

@include('AdminDashboard._chart-assets')

@push('scripts')
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
        const alertBox = document.getElementById('upload-alert');
        const fileInput = document.getElementById('excel_file');
        const previewButton = document.getElementById('preview-button');
        const saveButton = document.getElementById('save-button');
        const preview = document.getElementById('preview');
        const previewItems = document.getElementById('preview-items');
        let parsedDatasets = [];

        function notify(message, type = 'success') {
            alertBox.textContent = message;
            const noticeType = type === 'danger' ? 'danger' : type === 'warning' ? 'info' : 'success';
            alertBox.className = `modern-notice modern-notice--${noticeType}`;
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
                        <div class="ds-editor__values">
                            ${rows}
                            <button type="button" class="ds-editor__add"><i class="fa-solid fa-plus"></i> Tambah data</button>
                        </div>
                        <div class="ds-editor__preview"><canvas data-chart></canvas></div>
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

        previewButton.addEventListener('click', async () => {
            if (!fileInput.files.length) {
                notify('Pilih file Excel terlebih dahulu.', 'warning');
                return;
            }

            setBusy(previewButton, true, 'Memproses file...');
            try {
                const data = new FormData();
                data.append('excel_file', fileInput.files[0]);
                const response = await fetch('{{ route('admin.dashboard.extract') }}', requestOptions('POST', data));
                const result = await response.json();
                if (!response.ok || !result.success) throw new Error(result.message || 'Proses file gagal.');

                parsedDatasets = result.datasets;
                renderEditorList(previewItems, parsedDatasets);
                document.getElementById('preview-count').textContent = `${result.datasets.length} dataset siap disimpan`;
                preview.classList.remove('d-none');
                saveButton.disabled = false;
                notify('Data berhasil diparse. Sesuaikan jika perlu, lalu konfirmasi untuk menyimpan.');
            } catch (error) {
                parsedDatasets = [];
                saveButton.disabled = true;
                notify(error.message, 'danger');
            } finally {
                setBusy(previewButton, false);
            }
        });

        saveButton.addEventListener('click', async () => {
            const collected = Array.from(previewItems.querySelectorAll('.ds-editor')).map(readEditorDataset);
            if (!collected.length) {
                notify('Tidak ada dataset yang bisa disimpan.', 'danger');
                return;
            }
            if (!window.confirm('Simpan data ini? Dataset dashboard yang sudah ada akan digantikan.')) return;

            setBusy(saveButton, true, 'Menyimpan...');
            try {
                const response = await fetch('{{ route('admin.dashboard.save') }}', requestOptions('POST', JSON.stringify({ datasets: collected })));
                const result = await response.json();
                if (!response.ok || !result.success) throw new Error(result.message || 'Data gagal disimpan.');
                notify('Data berhasil dipublikasikan.', 'success');
                window.setTimeout(() => window.location.assign('{{ route('admin.dashboard') }}'), 600);
            } catch (error) {
                setBusy(saveButton, false);
                notify(error.message, 'danger');
            }
        });

        document.getElementById('clear-button').addEventListener('click', async () => {
            if (!window.confirm('Hapus semua data grafik di dashboard?')) return;
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