@extends('layouts.homelayout')

@section('title', $tag ? $tag->name : ($title ?? 'Kategori Informasi'))
@section('description', $tag ? ($tag->description ?? 'Informasi terkait ' . $tag->name) : ($description ?? 'Daftar Kategori Informasi S1 Informatika'))

@section('content')
    <section class="tag-hero public-intro-hero">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap mb-3">
                <a href="{{ route('home') }}" class="back-link"><i class="fa-solid fa-arrow-left"></i><img src="{{ asset('images/Logo2.png') }}" alt=""> <span>Kembali</span></a>
            </div>

            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <p class="eyebrow">Kategori</p>
                    <h1>{{ $tag ? $tag->name : 'Daftar Kategori' }}</h1>
                    <p class="lead">{{ $tag ? ($tag->description ?: 'Informasi terkini seputar ' . $tag->name . ' untuk Program Studi Sarjana Informatika.') : 'Jelajahi informasi berdasarkan topik dan kategori Program Studi Sarjana Informatika.' }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="home-content">
        <div class="container-fluid">
            <div class="topic-block">
                <div class="topic-header">
                    <a href="#">
                        <span class="topic-mark"></span>
                        <h2>{{ $tag ? 'List Informasi' : 'Kategori Tersedia' }}</h2>
                    </a>
                </div>

                @if ($tag)
                    <div class="row g-4">
                        @forelse($tag->posts->sortByDesc('updated_at') as $post)
                            <div class="col-md-4 col-lg-3">
                                <article class="news-card">
                                    <a href="{{ route('posts.show', ['slug' => $post->slug]) }}" class="card-link">
                                        <img src="{{ $post->image ? '/' . $post->image : asset('images/placeholder.png') }}" alt="{{ $post->title }}">
                                        <div class="news-card-body">
                                            <h3>{{ $post->title }}</h3>
                                            <p class="news-subtitle">{{ $post->subtitle }}</p>
                                            <div class="tag-list">
                                                @foreach ($post->tags->take(3) as $tagItem)
                                                    <span class="tag-pill">{{ $tagItem->name }}</span>
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
                                @include('partials.Empty')
                            </div>
                        @endforelse
                    </div>
                @else
                    <div class="row g-4">
                        @forelse($tags as $item)
                            <div class="col-md-4 col-lg-3">
                                <article class="news-card">
                                    <a href="{{ route('tags.show', ['slug' => $item->slug]) }}" class="card-link">
                                        <div class="news-card-body p-4">
                                            <h3>{{ $item->name }}</h3>
                                            <p class="news-subtitle">{{ $item->description ?: 'Lihat seluruh postingan dalam topik ' . $item->name }}</p>
                                            <div class="meta-line mt-3">
                                                <span>{{ $item->posts_count ?? $item->posts()->count() }} Informasi</span>
                                                <span class="text-primary font-semibold">Buka <i class="fa-solid fa-arrow-right"></i></span>
                                            </div>
                                        </div>
                                    </a>
                                </article>
                            </div>
                        @empty
                            <div class="col-12">
                                @include('partials.Empty')
                            </div>
                        @endforelse
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
