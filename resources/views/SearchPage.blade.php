@extends('layouts.homelayout')

@section('title', request()->get('search') ? request()->get('search') : 'Pencarian')

@section('content')
    <div class="search">
        <div class="top">
            <a href="{{ route('home') }}"><i class="fa-solid fa-arrow-left fa-lg"></i>Kembali</a>
        </div>
        <div class="search-tag">
            <div class="row">
                <label for="tag" class="form-label">
                    <h4>Pencarian Lain</h4>
                </label>
                <form method="GET" action="{{ route('posts.search') }}" class="col-md-3 d-flex" role="search">
                    <div class="dropdown">
                        <div class="search-bar">
                            <input class="form-control me-2" name="search" type="search"
                                value="{{ request()->get('search') }}" placeholder="Cari" aria-label="Search">
                        </div>
                        <div class="search-bar-tag">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Tag yang dipilih bisa lebih dari 1
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                @if (request()->query('tags'))
                                    @foreach ($tags as $tag)
                                        <label class="dropdown-item">
                                            <input name="tags[]" type="checkbox" class="checkbox-option"
                                                value="{{ $tag->id }}"
                                                {{ in_array($tag->id, request()->query('tags')) ? 'checked' : '' }}>
                                            {{ $tag->name }}
                                        </label>
                                    @endforeach
                                @else
                                    @foreach ($tags as $tag)
                                        <label class="dropdown-item">
                                            <input name="tags[]" type="checkbox" class="checkbox-option"
                                                value="{{ $tag->id }}"> {{ $tag->name }}
                                        </label>
                                    @endforeach
                                @endif
                            </div>
                            <button class="btn btn-secondary">Cari</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="search-hasil">
            <h1>Hasil Pencarian</h1>
            <hr>
            <div class="hasil-tag">
                @if (request()->query('search'))
                    <h5>Kata Kunci yang Dicari: <strong>{{ request()->get('search') }}</strong></h5>
                @endif
                @if (request()->query('tags'))
                    @foreach ($tags_search as $tag)
                        <a href="{{ route('viewTag', ['id' => $tag->id]) }}">{{ $tag->name }}</a>
                    @endforeach
                @endif
            </div>
            <div class="row card-search">
                <div class="d-flex">
                    @forelse($posts_search as $post)
                        <div class="col-md-3 card-holder">
                            <a class="text-decoration-none" href="{{ route('viewPost', ['slug' => $post->slug]) }}">
                                <div class="card">
                                    <img src="/{{ $post->image }}" class="card-img-top" alt="{{ $post->title }}">
                                    <div class="card-body">
                                        <h5 class="card-title truncate-1">{{ $post->title }}</h5>
                                        <p class="card-text truncate-1">{{ $post->subtitle }}</p>
                                        <div class="tag truncate-1">
                                            @foreach ($post->tags->slice(0, 3) as $key => $tag)
                                                <button type="button-tag" class="btn btn-secondary">
                                                    <h6>{{ $tag->name }}</h6>
                                                </button>
                                            @endforeach
                                        </div>
                                        <div class='card-date'>
                                            <p class="truncate-1">
                                                {{ $post->created_at->format('j F Y') }}
                                                ({{ $post->created_at->diffForHumans() }})
                                            </p>
                                            <p class="truncate-1">
                                                {{ $post->hasBeenUpdated() ? '' . $post->updated_at->format('j F Y') . ' (Edited)' : '' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @empty
                        @include('partials.Empty')
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
