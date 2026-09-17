@extends('layouts.homelayout')

@section('title', $post->title)
@section('description', $post->subtitle ?? 'Detail informasi program studi')

@section('content')
    <section class="post-article">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap mb-4">
                <a href="{{ route('home') }}" class="back-link"><i class="fa-solid fa-arrow-left"></i><img src="{{ asset('images/Logo2.png') }}" alt=""> <span>Kembali</span></a>
            </div>

            <article class="article-shell">
                <header class="article-header">
                    <div class="article-badge">Informasi</div>
                    <h1>{{ $post->title }}</h1>
                    <p class="article-subtitle">{{ $post->subtitle }}</p>
                    <div class="article-meta">
                        <span>{{ $post->created_at->format('j F Y') }}</span>
                        <span>{{ $post->created_at->diffForHumans() }}</span>
                        @if ($post->hasBeenUpdated())
                            <span>Diedit {{ $post->updated_at->format('j F Y') }}</span>
                        @endif
                    </div>
                </header>

                <div class="article-cover">
                    <img src="{{ $post->image ? '/' . $post->image : asset('images/placeholder.png') }}" alt="{{ $post->title }}">
                </div>

                <div class="article-body">
                    {!! $post->body !!}
                </div>

                <footer class="article-footer">
                    <div class="tag-list article-tags">
                        @foreach ($post->tags as $tag)
                            <a href="{{ route('tags.show', ['slug' => $tag->slug]) }}" class="tag-pill tag-link">{{ $tag->name }}</a>
                        @endforeach
                    </div>
                </footer>
            </article>
        </div>
    </section>
@endsection
