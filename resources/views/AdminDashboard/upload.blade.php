@extends('layouts.adminlayout')

@section('title', 'Upload Data Dashboard')

@section('content')
    <div class="admin modern-page">
        <h2 class="modern-page__heading">Manajemen Data Dashboard</h2>
        <div id="upload-alert" class="d-none" role="alert"></div>
        <section class="modern-card">
            <div class="upload-dropzone">
                <i class="fa-solid fa-cloud-arrow-up"></i>
                <p>Pilih file Excel (.xlsx, .xls) untuk diunggah</p>
                <input id="excel_file" class="form-control" type="file" accept=".xlsx,.xls">
                <div class="upload-actions">
                    <button id="preview-button" class="modern-button modern-button--soft" type="button"><i class="fa-regular fa-eye"></i> Preview Data</button>
                    <button id="save-button" class="modern-button modern-button--primary" type="button"><i class="fa-solid fa-upload"></i> Simpan ke Database</button>
                </div>
            </div>
        </section>

        <section id="preview" class="preview-section d-none">
            <h3 class="preview-section__title"><i class="fa-regular fa-file-lines"></i> Preview Data <span id="preview-count" class="preview-badge"></span></h3>
            <div id="preview-items" class="preview-grid"></div>
        </section>

        <div class="preview-section">
            <button id="clear-button" class="modern-button modern-button--danger" type="button"><i class="fa-solid fa-trash"></i> Hapus Semua Data Grafik</button>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const fileInput = document.getElementById('excel_file');
        const alertBox = document.getElementById('upload-alert');
        const preview = document.getElementById('preview');
        const previewItems = document.getElementById('preview-items');

        function notify(message, type = 'success') {
            alertBox.textContent = message;
            const noticeType = type === 'danger' ? 'danger' : type === 'warning' ? 'info' : 'success';
            alertBox.className = `modern-notice modern-notice--${noticeType}`;
        }

        async function upload(endpoint) {
            if (!fileInput.files.length) {
                notify('Pilih file Excel terlebih dahulu.', 'warning');
                return null;
            }

            const data = new FormData();
            data.append('excel_file', fileInput.files[0]);
            const response = await fetch(endpoint, { method: 'POST', body: data });
            const result = await response.json();

            if (!response.ok || !result.success) {
                throw new Error(result.message || 'Proses file gagal.');
            }

            return result;
        }

        document.getElementById('preview-button').addEventListener('click', async () => {
            try {
                const result = await upload('{{ url('/api/dashboard/extract') }}');
                if (!result) return;

                previewItems.replaceChildren();
                result.datasets.forEach((dataset) => {
                    const item = document.createElement('div');
                    item.className = 'preview-item';
                    item.innerHTML = `<h3>${escapeHtml(dataset.title)}</h3><dl><dt>Tipe</dt><dd>${escapeHtml(dataset.chart_type)}</dd><dt>Jumlah data</dt><dd>${dataset.labels?.length || 0}</dd><dt>Sumbu X</dt><dd>${escapeHtml(dataset.x_label || '-')}</dd><dt>Sumbu Y</dt><dd>${escapeHtml(dataset.y_label || '-')}</dd></dl>`;
                    previewItems.append(item);
                });
                document.getElementById('preview-count').textContent = `${result.datasets.length} grafik ditemukan`;
                preview.classList.remove('d-none');
                notify('Preview data berhasil dimuat.');
            } catch (error) {
                notify(error.message, 'danger');
            }
        });

        document.getElementById('save-button').addEventListener('click', async () => {
            try {
                const result = await upload('{{ url('/api/dashboard/pushdata') }}');
                if (!result) return;
                fileInput.value = '';
                preview.classList.add('d-none');
                notify('Data grafik berhasil disimpan ke database.');
            } catch (error) {
                notify(error.message, 'danger');
            }
        });

        document.getElementById('clear-button').addEventListener('click', async () => {
            if (!window.confirm('Hapus semua data grafik di dashboard?')) return;
            try {
                const response = await fetch('{{ url('/api/dashboard/cleardata') }}', { method: 'DELETE' });
                const result = await response.json();
                if (!response.ok || !result.success) throw new Error(result.message || 'Data gagal dihapus.');
                preview.classList.add('d-none');
                notify('Semua data grafik berhasil dihapus.');
            } catch (error) {
                notify(error.message, 'danger');
            }
        });

        function escapeHtml(value) {
            const element = document.createElement('span');
            element.textContent = value || '';
            return element.innerHTML;
        }
    </script>
@endpush
