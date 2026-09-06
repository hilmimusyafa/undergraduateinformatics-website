@extends('layouts.homelayout')

@section('title', 'Homepage')

@section('content')
    <div class="home">
        <div class="top">
            @include('partials.Alerts')
            <h1>Selamat Datang di Website Informasi Prodi S1 Informatika</h1>
            <hr>
        </div>
        <div class="row">
            <div class="col-md-9">
                @forelse($tags as $key => $data)
                    <div class="content-title d-flex">
                        <a class="d-flex" href="{{ route('viewTag', ['id' => $data->id]) }}">
                            <i class="fa-sharp fa-solid fa-square fa-xl"></i>
                            <h3>{{ $data->name }}</h3>
                            <i class="fa-solid fa-arrow-right fa-lg"></i>
                        </a>
                    </div>
                    <div class="row content-head">
                        <div class="content d-flex">
                            @forelse($data->posts->sortByDesc('updated_at')->slice(0, 3) as $key => $post)
                                <div class="col-md-4 card-holder">
                                    <div class="card">
                                        <a class="text-decoration-none" href="{{ route('viewPost', ['slug' => $post->slug]) }}">
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
                                        </a>
                                    </div>
                                </div>
                            @empty
                                @include('partials.Empty')
                            @endforelse
                        </div>
                    </div>
                @empty
                    @include('partials.Empty')
                @endforelse

            </div>
            <div class="col-md-3">
                <div class="content-title d-flex justify-content-center">
                    <h3>Info Terbaru</h3>
                </div>
                <div class="content-info col-md">
                    <ol class="d-flex">
                        <h5>
                            @forelse($posts->slice(0, 10) as $post)
                                <li><a class="truncate-3"
                                        href="{{ route('viewPost', ['slug' => $post->slug]) }}">{{ $post->title }}</a></li>
                            @empty
                                @include('partials.Empty')
                            @endforelse
                        </h5>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@endsection
