@extends('layouts.adminlayout')

@section('title', 'Dashboard')

@section('content')
    <div class="admin modern-page">
        <div class="dashboard-heading">
            <h2 class="modern-page__heading">Dashboard Statistik</h2>
            <button class="modern-button modern-button--primary" type="button" data-bs-toggle="modal" data-bs-target="#dashboard-upload-modal">
                <i class="fa-solid fa-file-arrow-up"></i> Upload Data Excel
            </button>
        </div>

        @if (! $dashboardTablesReady)
            <div class="empty-state modern-card">
                <i class="fa-solid fa-database"></i>
                <p>Database dashboard belum siap</p>
                <p>Tabel untuk menyimpan data dashboard belum dibuat. Setelah migration dashboard dijalankan, gunakan tombol Upload Data Excel di atas.</p>
            </div>
        @elseif ($datasets->isEmpty())
            <div class="empty-state modern-card">
                <i class="fa-regular fa-file-lines"></i>
                <p>Belum ada data yang dipublikasikan</p>
                <p>Upload file Excel untuk menerbitkan data pada dashboard.</p>
            </div>
        @else
            <div class="chart-grid">
                @foreach ($datasets as $dataset)
                    <article class="chart-card">
                        <h3 class="chart-card__title">{{ $dataset['title'] }}</h3>
                        <div class="chart-canvas"><canvas id="chart-{{ $dataset['id'] }}"></canvas></div>
                        @if ($dataset['x_label'])
                            <p class="chart-card__axis">{{ $dataset['x_label'] }}</p>
                        @endif
                    </article>
                @endforeach
            </div>
        @endif

        <div class="modal fade" id="dashboard-upload-modal" tabindex="-1" aria-labelledby="dashboard-upload-title" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content dashboard-upload-modal">
                    <div class="modal-header">
                        <div>
                            <p class="modal-kicker">EXCEL TO DATABASE</p>
                            <h2 class="modal-title fs-5" id="dashboard-upload-title">Upload data dashboard</h2>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div id="dashboard-upload-alert" class="d-none" role="alert"></div>
                        <div class="upload-dropzone">
                            <i class="fa-solid fa-file-excel"></i>
                            <p>Pilih file Excel (.xlsx atau .xls)</p>
                            <input id="dashboard-excel-file" class="form-control" type="file" accept=".xlsx,.xls">
                            <button id="dashboard-preview-button" class="modern-button modern-button--soft" type="button">
                                <i class="fa-regular fa-eye"></i> Parse &amp; Preview
                            </button>
                        </div>

                        <section id="dashboard-preview" class="preview-section d-none">
                            <h3 class="preview-section__title"><i class="fa-regular fa-file-lines"></i> Hasil parse <span id="dashboard-preview-count" class="preview-badge"></span></h3>
                            <p class="preview-help">Periksa data di bawah. Penyimpanan akan menggantikan dataset dashboard yang sebelumnya dipublikasikan.</p>
                            <div id="dashboard-preview-items" class="preview-grid"></div>
                        </section>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="modern-button modern-button--soft" data-bs-dismiss="modal">Batal</button>
                        <button id="dashboard-save-button" class="modern-button modern-button--primary" type="button" disabled>
                            <i class="fa-solid fa-circle-check"></i> Konfirmasi &amp; Simpan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const datasets = @json($datasets);
        const colors = ['#9F1521', '#E53E3E', '#F6AD55', '#48BB78', '#4299E1', '#805AD5'];

        datasets.forEach((dataset) => {
            const canvas = document.getElementById(`chart-${dataset.id}`);
            if (!canvas) return;

            new Chart(canvas, {
                type: ['bar', 'line', 'pie'].includes(dataset.chart_type?.toLowerCase()) ? dataset.chart_type.toLowerCase() : 'bar',
                data: {
                    labels: dataset.labels,
                    datasets: [{
                        label: dataset.y_label || 'Nilai',
                        data: dataset.values,
                        backgroundColor: dataset.chart_type?.toLowerCase() === 'pie'
                            ? dataset.labels.map((_, index) => colors[index % colors.length])
                            : '#9F1521',
                        borderColor: '#9F1521',
                        borderWidth: 2,
                        tension: 0.3,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: true } },
                },
            });
        });

        const dashboardTablesReady = @json($dashboardTablesReady);
        const uploadFile = document.getElementById('dashboard-excel-file');
        const uploadAlert = document.getElementById('dashboard-upload-alert');
        const previewButton = document.getElementById('dashboard-preview-button');
        const saveButton = document.getElementById('dashboard-save-button');
        const previewSection = document.getElementById('dashboard-preview');
        const previewItems = document.getElementById('dashboard-preview-items');
        let parsedFile = null;

        function showUploadNotice(message, type = 'info') {
            uploadAlert.textContent = message;
            uploadAlert.className = `modern-notice modern-notice--${type}`;
        }

        function setUploadBusy(button, isBusy, label) {
            button.disabled = isBusy;
            button.dataset.label ??= button.innerHTML;
            button.innerHTML = isBusy ? '<i class="fa-solid fa-spinner fa-spin"></i> ' + label : button.dataset.label;
        }

        function escapeHtml(value) {
            const element = document.createElement('span');
            element.textContent = value || '';
            return element.innerHTML;
        }

        uploadFile.addEventListener('change', () => {
            parsedFile = null;
            saveButton.disabled = true;
            previewSection.classList.add('d-none');
            uploadAlert.className = 'd-none';
        });

        previewButton.addEventListener('click', async () => {
            if (!uploadFile.files.length) {
                showUploadNotice('Pilih file Excel terlebih dahulu.', 'info');
                return;
            }

            setUploadBusy(previewButton, true, 'Memproses file...');
            try {
                const formData = new FormData();
                formData.append('excel_file', uploadFile.files[0]);
                const response = await fetch('{{ url('/api/dashboard/extract') }}', { method: 'POST', body: formData });
                const result = await response.json();

                if (!response.ok || !result.success) throw new Error(result.message || 'File tidak dapat diproses.');
                if (!result.datasets?.length) throw new Error('Tidak ada data numerik yang dapat dipublikasikan dari file ini.');

                parsedFile = uploadFile.files[0];
                previewItems.replaceChildren();
                result.datasets.forEach((dataset) => {
                    const item = document.createElement('article');
                    item.className = 'preview-item';
                    item.innerHTML = `<h3>${escapeHtml(dataset.title)}</h3><dl><dt>Tipe grafik</dt><dd>${escapeHtml(dataset.chart_type || '-')}</dd><dt>Jumlah data</dt><dd>${dataset.items?.length || 0}</dd><dt>Sumbu X</dt><dd>${escapeHtml(dataset.x_label || '-')}</dd><dt>Sumbu Y</dt><dd>${escapeHtml(dataset.y_label || '-')}</dd></dl>`;
                    previewItems.append(item);
                });
                document.getElementById('dashboard-preview-count').textContent = `${result.datasets.length} dataset siap disimpan`;
                previewSection.classList.remove('d-none');
                saveButton.disabled = !dashboardTablesReady;
                showUploadNotice(dashboardTablesReady ? 'Data berhasil diparse. Konfirmasi untuk menyimpan.' : 'Data berhasil diparse, tetapi tabel dashboard belum tersedia untuk menyimpan data.', dashboardTablesReady ? 'success' : 'info');
            } catch (error) {
                parsedFile = null;
                saveButton.disabled = true;
                showUploadNotice(error.message, 'danger');
            } finally {
                setUploadBusy(previewButton, false);
            }
        });

        saveButton.addEventListener('click', async () => {
            if (!parsedFile || !dashboardTablesReady) return;
            if (!window.confirm('Simpan data ini? Dataset dashboard yang sudah ada akan digantikan.')) return;

            setUploadBusy(saveButton, true, 'Menyimpan data...');
            try {
                const formData = new FormData();
                formData.append('excel_file', parsedFile);
                const response = await fetch('{{ url('/api/dashboard/pushdata') }}', { method: 'POST', body: formData });
                const result = await response.json();
                if (!response.ok || !result.success) throw new Error(result.message || 'Data gagal disimpan.');

                showUploadNotice('Data berhasil dipublikasikan. Dashboard akan dimuat ulang.', 'success');
                window.setTimeout(() => window.location.reload(), 700);
            } catch (error) {
                showUploadNotice(error.message, 'danger');
                setUploadBusy(saveButton, false);
            }
        });
    </script>
@endpush
