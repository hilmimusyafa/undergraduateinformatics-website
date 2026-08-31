@extends('layouts.adminlayout')

@section('title', 'Tambah Informasi Baru')

@section('content')
<!-- Page Header -->
<div class="admin-page-header">
    <div class="page-header-text">
        <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill mb-2">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Daftar Informasi
        </a>
        <h1>Tambah Informasi Baru</h1>
        <p>Lengkapi formulir di bawah ini untuk mempublikasikan pengumuman atau artikel baru.</p>
    </div>
</div>

<!-- Alerts Notification -->
@include('partials.Alerts')

<!-- Form Section Card -->
<div class="form-section-card">
    <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row g-4">
            <!-- Left Column: Text Inputs -->
            <div class="col-lg-7">
                <div class="mb-3">
                    <label for="judul" class="form-label">
                        <span>Judul Informasi</span> <span class="text-danger">*</span>
                    </label>
                    <input name="title" type="text" class="form-control form-control-lg" id="judul" value="{{ old('title') }}" placeholder="Contoh: Pendaftaran Seminar Proposal Periode Ganjil" required>
                </div>

                <div class="mb-3">
                    <label for="subjudul" class="form-label">
                        <span>Sub-Judul / Ringkasan</span> <span class="text-danger">*</span>
                    </label>
                    <textarea name="subtitle" class="form-control" id="subjudul" rows="2" placeholder="Ringkasan singkat yang akan muncul pada kartu informasi..." required>{{ old('subtitle') }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="deskripsi" class="form-label">
                        <span>Isi Lengkap Informasi</span> <span class="text-danger">*</span>
                    </label>
                    <textarea name="body" class="form-control" id="deskripsi">{{ old('body') }}</textarea>
                </div>
            </div>

            <!-- Right Column: Image & Tags -->
            <div class="col-lg-5">
                <!-- Cover Image Upload -->
                <div class="mb-4">
                    <label for="gambar" class="form-label">
                        <span>Gambar Sampul / Cover</span>
                    </label>
                    <div class="image-preview-box text-center" id="imagePreviewContainer" style="display: none;">
                        <img id="imagePreview" src="#" alt="Preview" class="img-fluid rounded mb-2">
                        <div>
                            <button type="button" class="btn btn-outline-danger btn-sm rounded-pill" onclick="clearImagePreview()">
                                <i class="fa-solid fa-trash-can me-1"></i> Batalkan Gambar
                            </button>
                        </div>
                    </div>
                    <input name="image" type="file" accept=".png, .jpeg, .jpg, .svg" class="form-control" id="gambar" onchange="previewSelectedImage(this)">
                    <div class="form-text small text-muted">Format yang didukung: JPG, PNG, JPEG. Ukuran maks: 2MB.</div>
                </div>

                <!-- Tag / Category Selection -->
                <div class="mb-4">
                    <label class="form-label d-flex align-items-center justify-content-between">
                        <span>Kategori / Tag Terkait</span> <span class="text-danger">*</span>
                    </label>

                    <div class="card border rounded-3 p-3 bg-light">
                        <input type="text" id="tagSearchInput" class="form-control form-control-sm mb-2" placeholder="Ketik untuk memfilter tag..." oninput="filterTagsList()">
                        <div id="tagOptionsList" style="max-height: 220px; overflow-y: auto;">
                            @foreach ($tags as $tag)
                                <div class="form-check py-1 tag-check-row">
                                    <input class="form-check-input" type="checkbox" name="tags[]" value="{{ $tag->id }}" id="tag_{{ $tag->id }}"
                                        {{ (is_array(old('tags')) && in_array($tag->id, old('tags'))) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-medium text-dark tag-name-label" for="tag_{{ $tag->id }}">
                                        {{ $tag->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <div class="form-text small text-muted mt-2">Pilih minimal 1 kategori. Tag default "S1 Informatika" akan disertakan secara otomatis.</div>
                    </div>
                </div>

                <!-- Submit Action Buttons -->
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-danger btn-lg rounded-pill fw-bold shadow-sm">
                        <i class="fa-solid fa-paper-plane me-1"></i> Publikasikan Informasi
                    </button>
                    <a href="{{ route('posts.index') }}" class="btn btn-light rounded-pill">
                        Batal & Kembali
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('extra-js')
<script>
    // Initialize CKEditor 5
    ClassicEditor
        .create(document.querySelector('#deskripsi'), {
            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', '|', 'undo', 'redo']
        })
        .catch(error => {
            console.error(error);
        });

    // Image preview helper
    function previewSelectedImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imagePreview').src = e.target.result;
                document.getElementById('imagePreviewContainer').style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function clearImagePreview() {
        const input = document.getElementById('gambar');
        input.value = '';
        document.getElementById('imagePreviewContainer').style.display = 'none';
    }

    // Filter tags helper
    function filterTagsList() {
        const query = document.getElementById('tagSearchInput').value.toLowerCase();
        const rows = document.querySelectorAll('.tag-check-row');
        rows.forEach(row => {
            const name = row.querySelector('.tag-name-label').textContent.toLowerCase();
            row.style.display = name.includes(query) ? 'block' : 'none';
        });
    }
</script>
@endsection
