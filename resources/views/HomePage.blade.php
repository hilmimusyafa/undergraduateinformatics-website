@extends('layouts.homelayout')

@section('title', 'Beranda')

@section('content')
    <!-- Hero Banner Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="hero-badge">
                        <i class="fa-solid fa-graduation-cap"></i>
                        <span>Portal Informasi Resmi Mahasiswa & Dosen</span>
                    </div>
                    <h1 class="hero-title">
                        Program Studi S1 Informatika<br>
                        Telkom University
                    </h1>
                    <p class="hero-subtitle">
                        Temukan informasi lengkap mengenai perkuliahan, registrasi FRS, program MBKM, seminar & sidang Tugas Akhir, serta pengumuman akademik terbaru.
                    </p>

                    <!-- Hero Search Box -->
                    <form method="GET" action="{{ route('posts.search') }}" class="hero-search-box">
                        <i class="fa-solid fa-magnifying-glass text-muted fs-5"></i>
                        <input type="text" name="search" placeholder="Cari topik, pengumuman, atau kata kunci..." aria-label="Cari">
                        <button type="submit" class="btn-hero-search">
                            <span>Cari</span>
                            <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </form>

                    <!-- Popular Tags Quick Access -->
                    @if(isset($tags_navbar) && $tags_navbar->isNotEmpty())
                        <div class="hero-quick-tags">
                            <span>Kategori Populer:</span>
                            @foreach ($tags_navbar->slice(0, 5) as $tag)
                                <a href="{{ route('viewTag', ['id' => $tag->id]) }}" class="hero-tag-chip">
                                    #{{ $tag->name }}
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content Body -->
    <section class="py-5">
        <div class="container">
            <!-- Alert Messages -->
            @include('partials.Alerts')

            <div class="row g-4">
                <!-- Left Column: Posts by Category / Tag -->
                <div class="col-lg-8 col-xl-9">
                    @forelse($tags as $data)
                        @if($data->posts->isNotEmpty())
                            <div class="mb-5">
                                <!-- Section Header -->
                                <div class="section-header">
                                    <h2 class="section-header-title">
                                        <span class="title-bar"></span>
                                        <span>{{ $data->name }}</span>
                                    </h2>
                                    <a href="{{ route('viewTag', ['id' => $data->id]) }}" class="section-header-link">
                                        <span>Lihat Semua</span>
                                        <i class="fa-solid fa-arrow-right-long"></i>
                                    </a>
                                </div>

                                <!-- Cards Grid (Max 3 latest posts per tag) -->
                                <div class="row g-4">
                                    @forelse($data->posts->sortByDesc('updated_at')->slice(0, 3) as $post)
                                        <div class="col-md-6 col-xl-4">
                                            <div class="custom-card">
                                                <a href="{{ route('viewPost', ['id' => $post->id]) }}" class="card-thumb-link">
                                                    <img src="/{{ $post->image }}" class="card-img-top" alt="{{ $post->title }}" onerror="this.onerror=null; this.src='/images/DummyImage.png';">
                                                </a>
                                                <div class="card-body">
                                                    <!-- Tag Badges -->
                                                    <div class="tag-badge-group">
                                                        @foreach ($post->tags->slice(0, 2) as $tag)
                                                            <a href="{{ route('viewTag', ['id' => $tag->id]) }}" class="tag-badge">
                                                                <i class="fa-solid fa-tag" style="font-size: 9px;"></i>
                                                                <span>{{ $tag->name }}</span>
                                                            </a>
                                                        @endforeach
                                                    </div>

                                                    <!-- Title & Subtitle -->
                                                    <a href="{{ route('viewPost', ['id' => $post->id]) }}" class="text-decoration-none">
                                                        <h3 class="card-title">{{ $post->title }}</h3>
                                                    </a>
                                                    <p class="card-text">{{ $post->subtitle }}</p>

                                                    <!-- Meta Footer -->
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
                        @endif
                    @empty
                        @include('partials.Empty')
                    @endforelse
                </div>

                <!-- Right Column: Sidebar Widgets -->
                <div class="col-lg-4 col-xl-3">
                    <!-- Latest Announcements Widget -->
                    <div class="sidebar-card">
                        <div class="sidebar-header">
                            <h4 class="sidebar-title">
                                <span class="live-indicator"></span>
                                <span>Informasi Terbaru</span>
                            </h4>
                        </div>
                        <ul class="latest-news-list">
                            @forelse($posts->slice(0, 8) as $post)
                                <li class="latest-news-item">
                                    <a href="{{ route('viewPost', ['id' => $post->id]) }}" class="latest-news-link">
                                        {{ $post->title }}
                                    </a>
                                    <div class="latest-news-date">
                                        <i class="fa-regular fa-clock"></i>
                                        <span>{{ $post->created_at->diffForHumans() }}</span>
                                    </div>
                                </li>
                            @empty
                                <li>
                                    <p class="text-muted small mb-0">Belum ada pengumuman.</p>
                                </li>
                            @endforelse
                        </ul>
                    </div>

                    <!-- Quick Directory Link Widget -->
                    <div class="sidebar-card bg-light border-0 shadow-sm">
                        <div class="sidebar-header border-0 pb-0 mb-3">
                            <h4 class="sidebar-title text-danger">
                                <i class="fa-solid fa-bookmark me-1"></i>
                                <span>Direktori Cepat</span>
                            </h4>
                        </div>
                        <p class="small text-muted mb-3">Akses cepat kumpulan berkas penting, RPS, SOP bimbingan, dan formulir akademik.</p>
                        <a href="{{ route('home.links') }}" class="btn btn-danger w-100 fw-bold rounded-pill shadow-sm">
                            <i class="fa-solid fa-folder-tree me-1"></i> Buka Link Penting
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
