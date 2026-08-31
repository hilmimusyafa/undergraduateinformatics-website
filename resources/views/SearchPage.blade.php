@extends('layouts.homelayout')

@section('title', request()->get('search') ? 'Pencarian: ' . request()->get('search') : 'Pencarian Informasi')

@section('content')
<div class="py-5">
    <div class="container">
        <!-- Breadcrumb Navigation -->
        <nav class="breadcrumb-nav mb-4" aria-label="breadcrumb">
            <a href="{{ route('home') }}"><i class="fa-solid fa-house"></i> Beranda</a>
            <i class="fa-solid fa-chevron-right text-muted" style="font-size: 11px;"></i>
            <span>Pencarian Informasi</span>
        </nav>

        <!-- Search & Filter Controls Card -->
        <div class="search-filter-card">
            <form method="GET" action="{{ route('posts.search') }}" id="searchFilterForm">
                <div class="row g-3 align-items-center mb-3">
                    <div class="col-md-9">
                        <div class="input-group input-group-lg shadow-sm rounded-4 overflow-hidden border">
                            <span class="input-group-text bg-white border-0 text-muted ps-3">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </span>
                            <input type="search" class="form-control border-0 shadow-none ps-2" name="search"
                                value="{{ request()->get('search') }}" placeholder="Cari judul, kata kunci, topik perkuliahan..." aria-label="Search">
                            @if(request()->get('search') || request()->query('tags'))
                                <a href="{{ route('posts.search') }}" class="btn btn-light border-0 text-muted d-flex align-items-center" title="Reset Filter">
                                    <i class="fa-solid fa-xmark"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-danger btn-lg w-100 rounded-4 fw-bold shadow-sm">
                            <i class="fa-solid fa-filter me-1"></i> Terapkan Filter
                        </button>
                    </div>
                </div>

                <!-- Tag Multi-select Chips -->
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <label class="form-label text-dark fw-bold small mb-0">
                            <i class="fa-solid fa-tags text-danger me-1"></i> Filter Berdasarkan Kategori:
                        </label>
                        <span class="text-muted small">Pilih satu atau lebih kategori</span>
                    </div>

                    <div class="filter-tags-container">
                        @foreach ($tags as $tag)
                            <div class="tag-checkbox-chip">
                                <input type="checkbox" name="tags[]" id="tag_check_{{ $tag->id }}" value="{{ $tag->id }}"
                                    {{ request()->query('tags') && in_array($tag->id, (array) request()->query('tags')) ? 'checked' : '' }}
                                    onchange="document.getElementById('searchFilterForm').submit()">
                                <label for="tag_check_{{ $tag->id }}">
                                    <span>{{ $tag->name }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </form>
        </div>

        <!-- Search Results Header -->
        <div class="section-header">
            <h2 class="section-header-title">
                <span class="title-bar"></span>
                <span>Hasil Pencarian</span>
            </h2>
            <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill fw-bold">
                {{ $posts_search->count() }} Hasil Ditemukan
            </span>
        </div>

        <!-- Active Filters Summary -->
        @if(request()->get('search') || request()->query('tags'))
            <div class="d-flex align-items-center gap-2 flex-wrap mb-4 bg-light p-3 rounded-3 border">
                <span class="small fw-bold text-muted">Filter aktif:</span>
                @if(request()->get('search'))
                    <span class="badge bg-white text-dark border px-3 py-2 rounded-pill">
                        Kata kunci: <strong>"{{ request()->get('search') }}"</strong>
                    </span>
                @endif
                @if(request()->query('tags'))
                    @foreach ($tags_search as $tag)
                        <span class="badge bg-danger text-white px-3 py-2 rounded-pill">
                            #{{ $tag->name }}
                        </span>
                    @endforeach
                @endif
                <a href="{{ route('posts.search') }}" class="small text-danger fw-semibold ms-auto text-decoration-none">
                    <i class="fa-solid fa-rotate-left me-1"></i> Reset Semua
                </a>
            </div>
        @endif

        <!-- Search Results Grid -->
        <div class="row g-4">
            @forelse($posts_search as $post)
                <div class="col-md-6 col-lg-4">
                    <div class="custom-card">
                        <a href="{{ route('viewPost', ['id' => $post->id]) }}" class="card-thumb-link">
                            <img src="/{{ $post->image }}" class="card-img-top" alt="{{ $post->title }}" onerror="this.onerror=null; this.src='/images/DummyImage.png';">
                        </a>
                        <div class="card-body">
                            <div class="tag-badge-group">
                                @foreach ($post->tags->slice(0, 2) as $tag)
                                    <a href="{{ route('viewTag', ['id' => $tag->id]) }}" class="tag-badge">
                                        <i class="fa-solid fa-tag" style="font-size: 9px;"></i>
                                        <span>{{ $tag->name }}</span>
                                    </a>
                                @endforeach
                            </div>

                            <a href="{{ route('viewPost', ['id' => $post->id]) }}" class="text-decoration-none">
                                <h3 class="card-title">{{ $post->title }}</h3>
                            </a>
                            <p class="card-text">{{ $post->subtitle }}</p>

                            <div class="card-footer-meta">
                                <span class="date-info">
                                    <i class="fa-regular fa-calendar text-muted"></i>
                                    <span>{{ $post->created_at->translatedFormat('d M Y') }}</span>
                                </span>
                                <a href="{{ route('viewPost', ['id' => $post->id]) }}" class="fw-bold text-danger text-decoration-none small">
                                    Baca <i class="fa-solid fa-chevron-right ms-1" style="font-size: 10px;"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="w-100 py-5 my-3 text-center bg-white rounded-4 border shadow-sm">
                        <div class="mb-3">
                            <div class="d-inline-flex align-items-center justify-content-center bg-danger-subtle text-danger rounded-circle" style="width: 72px; height: 72px;">
                                <i class="fa-solid fa-magnifying-glass fs-2"></i>
                            </div>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Tidak Ada Hasil yang Cocok</h5>
                        <p class="text-muted small mb-4">Coba gunakan kata kunci lain atau kurangi filter kategori yang dipilih.</p>
                        <a href="{{ route('posts.search') }}" class="btn btn-outline-danger btn-sm rounded-pill px-4">
                            Tampilkan Semua Informasi
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
