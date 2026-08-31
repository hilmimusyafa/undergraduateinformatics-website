@extends('layouts.homelayout')

@section('title', 'Kategori: ' . $tag->name)

@section('content')
<div class="py-5">
    <div class="container">
        <!-- Breadcrumbs -->
        <nav class="breadcrumb-nav mb-4" aria-label="breadcrumb">
            <a href="{{ route('home') }}"><i class="fa-solid fa-house"></i> Beranda</a>
            <i class="fa-solid fa-chevron-right text-muted" style="font-size: 11px;"></i>
            <a href="{{ route('posts.search') }}">Kategori</a>
            <i class="fa-solid fa-chevron-right text-muted" style="font-size: 11px;"></i>
            <span>{{ $tag->name }}</span>
        </nav>

        <!-- Tag Hero Banner Card -->
        <div class="card border-0 rounded-4 shadow-sm bg-white p-4 p-md-5 mb-5 position-relative overflow-hidden">
            <div class="position-absolute top-0 end-0 p-4 opacity-10 d-none d-md-block">
                <i class="fa-solid fa-folder-open text-danger" style="font-size: 140px;"></i>
            </div>
            <div class="row position-relative" style="z-index: 2;">
                <div class="col-lg-8">
                    <div class="d-inline-flex align-items-center gap-2 bg-danger-subtle text-danger px-3 py-1 rounded-pill fw-bold small mb-3">
                        <i class="fa-solid fa-tag"></i>
                        <span>Kategori Informasi</span>
                    </div>
                    <h1 class="display-6 fw-bold text-dark mb-3">{{ $tag->name }}</h1>
                    <p class="text-muted fs-6 mb-4 leading-relaxed">
                        {{ $tag->description ?? 'Daftar kumpulan informasi resmi, pengumuman, dan berita terkait kategori ' . $tag->name . ' untuk Program Studi S1 Informatika Telkom University.' }}
                    </p>
                    <div class="d-flex align-items-center gap-3">
                        <span class="badge bg-light text-dark border px-3 py-2 rounded-pill fw-semibold">
                            <i class="fa-solid fa-newspaper text-danger me-1"></i>
                            {{ $tag->posts->count() }} Informasi Tersedia
                        </span>
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                            <i class="fa-solid fa-arrow-left me-1"></i> Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Header -->
        <div class="section-header">
            <h2 class="section-header-title">
                <span class="title-bar"></span>
                <span>Daftar Informasi Terkait {{ $tag->name }}</span>
            </h2>
        </div>

        <!-- Posts Grid -->
        <div class="row g-4">
            @forelse($tag->posts->sortByDesc('updated_at') as $post)
                <div class="col-md-6 col-lg-4">
                    <div class="custom-card">
                        <a href="{{ route('viewPost', ['id' => $post->id]) }}" class="card-thumb-link">
                            <img src="/{{ $post->image }}" class="card-img-top" alt="{{ $post->title }}" onerror="this.onerror=null; this.src='/images/DummyImage.png';">
                        </a>
                        <div class="card-body">
                            <div class="tag-badge-group">
                                @foreach ($post->tags->slice(0, 2) as $t)
                                    <a href="{{ route('viewTag', ['id' => $t->id]) }}" class="tag-badge">
                                        <i class="fa-solid fa-tag" style="font-size: 9px;"></i>
                                        <span>{{ $t->name }}</span>
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
                    @include('partials.Empty')
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
