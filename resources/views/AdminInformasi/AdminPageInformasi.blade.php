@extends('layouts.adminlayout')

@section('title', 'Manajemen Informasi')

@section('content')
<!-- Page Header -->
<div class="admin-page-header">
    <div class="page-header-text">
        <h1>Daftar Informasi & Pengumuman</h1>
        <p>Kelola seluruh artikel, berita akademik, pengumuman perkuliahan, dan informasi kemahasiswaan.</p>
    </div>
    <div class="header-action-group">
        <a href="{{ route('posts.create') }}" class="btn-admin-primary">
            <i class="fa-solid fa-plus"></i>
            <span>Tambah Informasi Baru</span>
        </a>
    </div>
</div>

<!-- Alerts Notification -->
@include('partials.Alerts')

<!-- Main Table Card -->
<div class="admin-card">
    <div class="admin-card-header">
        <div class="fw-bold text-dark fs-6">
            <i class="fa-solid fa-newspaper text-danger me-2"></i>
            <span>Total: {{ $posts->count() }} Informasi</span>
        </div>
        <form method="GET" action="{{ route('posts.index') }}" class="admin-search-form">
            <i class="fa-solid fa-magnifying-glass admin-search-icon"></i>
            <input class="admin-search-input" name="search" type="search" placeholder="Cari judul atau isi..."
                value="{{ request()->get('search') }}" aria-label="Search">
        </form>
    </div>

    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 80px;">Cover</th>
                    <th>Judul & Sub-Judul</th>
                    <th style="width: 200px;">Kategori / Tag</th>
                    <th style="width: 140px;">Tanggal</th>
                    <th style="width: 140px; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($posts as $data)
                    <tr>
                        <td>
                            <img src="/{{ $data->image }}" alt="{{ $data->title }}" class="table-thumb-img" onerror="this.onerror=null; this.src='/images/DummyImage.png';">
                        </td>
                        <td>
                            <div class="fw-bold text-dark mb-1">{{ Str::limit($data->title, 80) }}</div>
                            <div class="small text-muted">{{ Str::limit($data->subtitle, 90) }}</div>
                        </td>
                        <td>
                            <div class="d-flex flex-wrap gap-1">
                                @foreach ($data->tags as $post_tag)
                                    <span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-1 small">
                                        {{ $post_tag->name }}
                                    </span>
                                @endforeach
                            </div>
                        </td>
                        <td>
                            <div class="small fw-semibold text-dark">{{ $data->created_at->format('d/m/Y') }}</div>
                            <div class="text-muted" style="font-size: 11px;">{{ $data->created_at->diffForHumans() }}</div>
                        </td>
                        <td class="table-action-cell">
                            <a class="btn-action-edit" href="{{ route('posts.edit', ['post' => $data->id]) }}">
                                <i class="fa-solid fa-pen-to-square"></i> Edit
                            </a>
                            <button type="button" class="btn-action-delete" data-bs-toggle="modal" data-bs-target="#confirmModal-{{ $data->id }}">
                                <i class="fa-solid fa-trash-can"></i> Hapus
                            </button>
                        </td>
                    </tr>

                    <!-- Delete Confirmation Modal -->
                    <div class="modal fade" id="confirmModal-{{ $data->id }}" tabindex="-1"
                        aria-labelledby="confirmModalLabel-{{ $data->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-4 border-0 shadow">
                                <div class="modal-header border-0 pb-0">
                                    <h5 class="modal-title fw-bold text-dark" id="confirmModalLabel-{{ $data->id }}">
                                        <i class="fa-solid fa-triangle-exclamation text-danger me-2"></i>
                                        Konfirmasi Hapus
                                    </h5>
                                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body py-4">
                                    <p class="mb-2">Apakah Anda yakin ingin menghapus informasi berikut?</p>
                                    <div class="p-3 bg-light rounded-3 border">
                                        <div class="fw-bold text-dark">{{ $data->title }}</div>
                                    </div>
                                    <p class="text-danger small mt-2 mb-0">Tindakan ini permanen dan tidak dapat dibatalkan.</p>
                                </div>
                                <div class="modal-footer border-0 pt-0">
                                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                    <form action="{{ route('posts.destroy', ['post' => $data->id]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">Hapus Sekarang</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="5" class="py-5 text-center">
                            @include('partials.Empty')
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
