@extends('layouts.homelayout')

@section('title', 'Direktori Link Penting')

@section('content')
<div class="py-5">
    <div class="container">
        <!-- Breadcrumb Navigation -->
        <nav class="breadcrumb-nav mb-4" aria-label="breadcrumb">
            <a href="{{ route('home') }}"><i class="fa-solid fa-house"></i> Beranda</a>
            <i class="fa-solid fa-chevron-right text-muted" style="font-size: 11px;"></i>
            <span>Link Penting</span>
        </nav>

        <!-- Header Banner -->
        <div class="card border-0 rounded-4 shadow-sm bg-white p-4 p-md-5 mb-5">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="d-inline-flex align-items-center gap-2 bg-danger-subtle text-danger px-3 py-1 rounded-pill fw-bold small mb-3">
                        <i class="fa-solid fa-folder-tree"></i>
                        <span>Direktori Tautan Resmi</span>
                    </div>
                    <h1 class="display-6 fw-bold text-dark mb-3">Kumpulan Link Penting Program Studi</h1>
                    <p class="text-muted fs-6 mb-0">
                        Kompilasi tautan penting mengenai panduan MBKM, sistem monitoring Tugas Akhir, portal akademik, formulir pendaftaran, dan layanan kemahasiswaan S1 Informatika Telkom University.
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                    <a href="{{ route('viewFeedback') }}" class="btn btn-outline-danger rounded-pill px-4 fw-semibold">
                        <i class="fa-solid fa-comment-dots me-1"></i> Usulkan Tautan Baru
                    </a>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Left Column: Sections & Links -->
            <div class="col-lg-8 col-xl-9">
                @forelse ($sections as $section)
                    <div id="section-{{ $section->id }}" class="section-block">
                        <h3 class="section-block-title">
                            <i class="fa-solid fa-folder-open"></i>
                            <span>{{ $section->name }}</span>
                        </h3>

                        <div class="link-items-container">
                            @forelse($section->important_links->sortBy('name') as $link)
                                <div class="link-item-row">
                                    <div class="link-item-info">
                                        <h5 class="link-item-name">{{ $link->name }}</h5>
                                        <a href="{{ $link->link }}" target="_blank" rel="noopener noreferrer" class="link-item-url">
                                            <i class="fa-solid fa-arrow-up-right-from-square me-1"></i>
                                            <span>{{ Str::limit($link->link, 70) }}</span>
                                        </a>
                                    </div>
                                    <div class="link-item-actions">
                                        <button type="button" class="btn-action-pill" onclick="copyToClipboard('{{ $link->link }}', this)" title="Salin Tautan">
                                            <i class="fa-regular fa-copy"></i>
                                            <span class="d-none d-sm-inline">Salin</span>
                                        </button>
                                        <a href="{{ $link->link }}" target="_blank" rel="noopener noreferrer" class="btn-action-pill btn-primary-pill" title="Kunjungi Website">
                                            <i class="fa-solid fa-external-link"></i>
                                            <span class="d-none d-sm-inline">Buka</span>
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="p-3 text-center text-muted bg-light rounded-3">
                                    <p class="small mb-0">Belum ada tautan yang ditambahkan di section ini.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @empty
                    @include('partials.Empty')
                @endforelse
            </div>

            <!-- Right Column: Navigation Table of Contents -->
            <div class="col-lg-4 col-xl-3">
                <div class="sidebar-card sticky-top" style="top: 90px;">
                    <div class="sidebar-header">
                        <h4 class="sidebar-title">
                            <i class="fa-solid fa-list-ul text-danger"></i>
                            <span>Daftar Kategori Link</span>
                        </h4>
                    </div>
                    <ul class="list-unstyled mb-0">
                        @forelse ($sections as $section)
                            <li class="mb-2">
                                <a href="#section-{{ $section->id }}" class="d-flex align-items-center justify-content-between p-2 rounded text-decoration-none text-dark hover-bg-light fw-medium small">
                                    <span class="text-truncate">{{ $section->name }}</span>
                                    <span class="badge bg-danger-subtle text-danger rounded-pill">{{ $section->important_links->count() }}</span>
                                </a>
                            </li>
                        @empty
                            <li><p class="text-muted small mb-0">Belum ada kategori.</p></li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
