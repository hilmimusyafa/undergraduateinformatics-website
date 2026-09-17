@extends('layouts.homelayout')

@section('title', $tag->name)
@section('description', $tag->description ?? 'Informasi terkait ' . $tag->name)

@section('content')
    <section class="tag-hero">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap mb-3">
                <a href="{{ route('home') }}" class="back-link"><i class="fa-solid fa-arrow-left"></i><img src="{{ asset('images/Logo2.png') }}" alt=""> <span>Kembali</span></a>
            </div>

            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <p class="eyebrow">Kategori</p>
                    <h1>{{ $tag->name }}</h1>
                    <p class="lead">{{ $tag->description ?: 'Informasi terkini seputar ' . $tag->name . ' untuk Program Studi Sarjana Informatika.' }}</p>
                </div>
                <div class="col-lg-5">
                    <div class="hero-image-card">
                        <img src="{{ asset('images/placeholder.png') }}" alt="{{ $tag->name }}">
                    </div>
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
                        <h2>List Informasi</h2>
                    </a>
                </div>

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
                                            <span>{{ $post->created_at->format('j F Y') }}</span>
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
            </div>
        </div>
    </section>
@endsection
