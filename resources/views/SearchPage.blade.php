@extends('layouts.homelayout')

@section('title', $search !== '' ? 'Hasil pencarian: ' . $search : 'Pencarian')
@section('description', 'Cari informasi terkait program studi Sarjana Informatika Telkom University.')

@section('content')
    <section class="tag-hero public-intro-hero">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap mb-3">
                <a href="{{ route('home') }}" class="back-link"><i class="fa-solid fa-arrow-left"></i><img src="{{ asset('images/Logo2.png') }}" alt=""> <span>Kembali</span></a>
            </div>

            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <p class="eyebrow">Pencarian</p>
                    <h1>{{ $search !== '' ? 'Hasil Pencarian' : 'Cari Informasi' }}</h1>
                    <p class="lead">Temukan artikel, informasi, dan materi terbaru di Program Studi Sarjana Informatika.</p>
                    <p class="hero-detail">Cari berdasarkan kata kunci atau gunakan filter kategori untuk menemukan pengumuman, panduan, materi, dan informasi akademik yang relevan.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="home-content">
        <div class="container-fluid">
            <div class="topic-block search-panel">
                <form method="GET" action="{{ route('posts.search') }}" class="search-form">
                    <div class="search-input-wrapper">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <label for="search" class="search-field-label">
                                <i class="fa-solid fa-magnifying-glass text-danger"></i>
                                <span>Kata Kunci</span>
                            </label>
                            @if ($search !== '' || ! $tags_search->isEmpty())
                                <a href="{{ route('posts.search') }}" class="search-reset-link">
                                    <i class="fa-solid fa-rotate-left"></i>
                                    <span>Reset Filter</span>
                                </a>
                            @endif
                        </div>
                        <div class="search-input-group">
                            <div class="search-input-inner">
                                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                                <input id="search" class="form-control" name="search" type="search" value="{{ $search }}" placeholder="Cari judul, subtitle, atau isi artikel..." autocomplete="off">
                            </div>
                            <button type="submit" class="btn search-btn search-button">
                                <i class="fa-solid fa-magnifying-glass me-1"></i> Cari
                            </button>
                        </div>
                    </div>

                    <div class="filter-tag-wrapper">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <label class="search-field-label">
                                    <i class="fa-solid fa-tags text-danger"></i>
                                    <span>Filter Tag</span>
                                </label>
                                @if (! $tags_search->isEmpty())
                                    <span class="badge bg-danger text-white rounded-pill px-2 py-1 font-monospace" style="font-size: 0.72rem;">
                                        {{ $tags_search->count() }} dipilih
                                    </span>
                                @endif
                            </div>
                            <small class="text-muted d-none d-sm-inline" style="font-size: 0.78rem;">Pilih tag untuk menyaring hasil</small>
                        </div>

                        <div class="tag-filter-container checkbox-group">
                            <div class="tag-filter-group">
                                @foreach ($tags as $tag)
                                    @php
                                        $isChecked = in_array($tag->id, $tags_search->pluck('id')->toArray(), true);
                                    @endphp
                                    <label class="tag-filter-chip check-option {{ $isChecked ? 'is-active' : '' }}" for="tag-{{ $tag->id }}">
                                        <input type="checkbox" id="tag-{{ $tag->id }}" name="tags[]" value="{{ $tag->id }}" {{ $isChecked ? 'checked' : '' }} class="tag-filter-chip__input">
                                        <span class="tag-filter-chip__check">
                                            <i class="fa-solid fa-check"></i>
                                        </span>
                                        <span class="tag-filter-chip__text">{{ $tag->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="topic-block">
                <div class="topic-header">
                    <a href="#">
                        <span class="topic-mark"></span>
                        <h2>Hasil Pencarian</h2>
                    </a>
                </div>

                @if ($search !== '' || ! $tags_search->isEmpty())
                    <div class="filter-summary">
                        @if ($search !== '')
                            <span>Kata kunci: <strong>{{ $search }}</strong></span>
                        @endif
                        @foreach ($tags_search as $tag)
                            <span class="tag-pill">{{ $tag->name }}</span>
                        @endforeach
                    </div>
                @endif

                <div class="row g-4">
                    @forelse($posts_search as $post)
                        <div class="col-md-4 col-lg-3">
                            <article class="news-card">
                                <a href="{{ route('posts.show', ['slug' => $post->slug]) }}" class="card-link">
                                    <img src="{{ $post->image ? '/' . $post->image : asset('images/placeholder.png') }}" alt="{{ $post->title }}">
                                    <div class="news-card-body">
                                        <h3>{{ $post->title }}</h3>
                                        <p class="news-subtitle">{{ $post->subtitle }}</p>
                                        <div class="tag-list">
                                            @foreach ($post->tags->take(3) as $tag)
                                                <span class="tag-pill">{{ $tag->name }}</span>
                                            @endforeach
                                        </div>
                                        <div class="meta-line">
                                            <span>{{ $post->created_at?->format('j F Y') }}</span>
                                            <span>{{ $post->hasBeenUpdated() ? 'Diedit' : 'Baru' }}</span>
                                        </div>
                                    </div>
                                </a>
                            </article>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="empty-state">
                                @include('partials.Empty')
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const chipInputs = document.querySelectorAll('.tag-filter-chip__input');
    chipInputs.forEach(function (input) {
        input.addEventListener('change', function () {
            const chip = this.closest('.tag-filter-chip');
            if (chip) {
                chip.classList.toggle('is-active', this.checked);
            }
        });
    });
});
</script>
@endpush
