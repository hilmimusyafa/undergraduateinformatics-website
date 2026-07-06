@extends('layouts.homelayout')

@section('title', $tag->name)

@section('content')
    <div class="post">
        <div class="top">
            <a href="{{ route('home') }}"><i class="fa-solid fa-arrow-left fa-lg"></i>Kembali</a>
            <h1>Informasi Terkait {{ $tag->name }} untuk Prodi S1 Informatika</h1>
            <hr>
        </div>
        <div class="post-deskripsi">
            <div class="row deskripsi-gambar d-flex justify-content-center">
                <img src="/images/DummyImage.png" alt="{{ $tag->name }}">
            </div>
            <div class="row deskripsi-text d-flex">
                <p>{{ $tag->description }}</p>
            </div>
        </div>
        <div class="semua-post">
            <h1>List Informasi</h1>
            <hr>
            <div class="row card-post">
                <div class="d-flex">
                    @forelse($tag->posts->sortByDesc('updated_at') as $post)
                        <div class="col-md-3 card-holder">
                            <div class="card">
                                <a class="text-decoration-none" href="{{ route('viewPost', ['id' => $post->id]) }}">
                                    <img src="/{{ $post->image }}" class="card-img-top" alt="...">
                                    <div class="card-body">
                                        <h5 class="card-title truncate-1">{{ $post->title }}</h5>
                                        <p class="card-text truncate-1">{{ $post->subtitle }}</p>
                                        <div class="tag truncate-1">
                                            @foreach ($post->tags->slice(0, 3) as $tag)
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
        </div>
    </div>
@endsection
