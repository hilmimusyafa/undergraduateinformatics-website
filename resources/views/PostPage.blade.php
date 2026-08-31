@extends('layouts.homelayout')

@section('title', $post->title)

@section('content')
<div class="post-detail-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-9">
                <!-- Breadcrumb Navigation -->
                <nav class="breadcrumb-nav" aria-label="breadcrumb">
                    <a href="{{ route('home') }}"><i class="fa-solid fa-house"></i> Beranda</a>
                    <i class="fa-solid fa-chevron-right text-muted" style="font-size: 11px;"></i>
                    @if($post->tags->isNotEmpty())
                        <a href="{{ route('viewTag', ['id' => $post->tags->first()->id]) }}">
                            {{ $post->tags->first()->name }}
                        </a>
                        <i class="fa-solid fa-chevron-right text-muted" style="font-size: 11px;"></i>
                    @endif
                    <span class="text-truncate" style="max-width: 280px;">{{ $post->title }}</span>
                </nav>

                <!-- Article Header -->
                <div class="post-detail-header">
                    <!-- Category Badges -->
                    <div class="tag-badge-group mb-3">
                        @foreach ($post->tags as $tag)
                            <a href="{{ route('viewTag', ['id' => $tag->id]) }}" class="tag-badge fs-6 py-1 px-3">
                                <i class="fa-solid fa-tag me-1" style="font-size: 11px;"></i>
                                <span>{{ $tag->name }}</span>
                            </a>
                        @endforeach
                    </div>

                    <!-- Main Title & Subtitle -->
                    <h1 class="post-detail-title">{{ $post->title }}</h1>
                    @if($post->subtitle)
                        <h4 class="post-detail-subtitle">{{ $post->subtitle }}</h4>
                    @endif

                    <!-- Author & Publication Date Bar -->
                    <div class="post-author-bar">
                        <div class="author-info">
                            <div class="author-avatar">
                                <i class="fa-solid fa-user-tie"></i>
                            </div>
                            <div>
                                <div class="author-name">Program Studi S1 Informatika</div>
                                <div class="small text-muted">Fakultas Informatika, Telkom University</div>
                            </div>
                        </div>
                        <div class="post-date-badge">
                            <i class="fa-regular fa-calendar-check text-danger fs-5"></i>
                            <div>
                                <div class="fw-semibold text-dark">{{ $post->created_at->translatedFormat('l, d F Y') }}</div>
                                <div class="small text-muted">
                                    {{ $post->created_at->diffForHumans() }}
                                    @if($post->hasBeenUpdated())
                                        &bull; <span class="text-primary"><i class="fa-solid fa-pen-to-square"></i> Diperbarui {{ $post->updated_at->translatedFormat('d M Y') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Featured Image -->
                @if($post->hasImage())
                    <div class="text-center mb-4">
                        <img src="/{{ $post->image }}" alt="{{ $post->title }}" class="post-detail-featured-image img-fluid" onerror="this.onerror=null; this.src='/images/DummyImage.png';">
                    </div>
                @endif

                <!-- Article Body (Rich Text) -->
                <article class="post-detail-body">
                    {!! $post->body !!}
                </article>

                <!-- Footer Article: Tags & Actions -->
                <div class="post-detail-footer">
                    <div>
                        <span class="fw-bold text-dark me-2"><i class="fa-solid fa-tags text-danger me-1"></i> Label:</span>
                        @foreach ($post->tags as $tag)
                            <a href="{{ route('viewTag', ['id' => $tag->id]) }}" class="badge bg-light text-secondary border text-decoration-none px-3 py-2 me-1 rounded-pill">
                                {{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                    
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" onclick="copyToClipboard(window.location.href, this)">
                            <i class="fa-solid fa-share-nodes me-1"></i> Bagikan
                        </button>
                        <a href="{{ route('home') }}" class="btn btn-danger btn-sm rounded-pill px-3">
                            <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
