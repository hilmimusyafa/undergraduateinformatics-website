@extends('layouts.homelayout')

@section('title', $search !== '' ? 'Hasil pencarian: ' . $search : 'Pencarian')
@section('description', 'Cari informasi terkait program studi Sarjana Informatika Telkom University.')

@section('content')
    <section class="tag-hero">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap mb-3">
                <a href="{{ route('home') }}" class="back-link"><i class="fa-solid fa-arrow-left"></i><img src="{{ asset('images/Logo2.png') }}" alt=""> <span>Kembali</span></a>
            </div>

            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <p class="eyebrow">Pencarian</p>
                    <h1>{{ $search !== '' ? 'Hasil Pencarian' : 'Cari Informasi' }}</h1>
                    <p class="lead">Temukan artikel, informasi, dan materi terbaru di Program Studi Sarjana Informatika.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="home-content">
        <div class="container-fluid">
            <div class="topic-block search-panel">
                <form method="GET" action="{{ route('posts.search') }}" class="search-form">
                    <div class="search-field">
                        <label for="search" class="form-label">Kata kunci</label>
                        <input id="search" class="form-control" name="search" type="search" value="{{ $search }}" placeholder="Cari judul, subtitle, atau isi artikel">
                    </div>

                    <div class="search-field checkbox-field">
                        <label class="form-label">Filter tag</label>
                        <div class="checkbox-group">
                            @foreach ($tags as $tag)
                                <label class="check-option">
                                    <input type="checkbox" name="tags[]" value="{{ $tag->id }}" {{ in_array($tag->id, $tags_search->pluck('id')->toArray(), true) ? 'checked' : '' }}>
                                    <span>{{ $tag->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="search-action">
                        <button type="submit" class="btn btn-primary search-button">Cari</button>
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
                                            <span>{{ $post->created_at->format('j F Y') }}</span>
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
