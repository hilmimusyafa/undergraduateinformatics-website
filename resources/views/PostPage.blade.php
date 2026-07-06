@extends('layouts.homelayout')

@section('title', $post->title)

@section('content')
    <div class="post">
        <div class="top">
            <a href="{{ route('home') }}"><i class="fa-solid fa-arrow-left fa-lg"></i>Kembali</a>
        </div>
        <div class="post-deskripsi">
            <div class="row deskripsi-gambar d-flex justify-content-center">
                <img src="/{{ $post->image }}" alt="{{ $post->title }}">
            </div>
            <div class="row postingan-text d-flex">
                <h1>{{ $post->title }}</h1>
                <h5>{{ $post->subtitle }}</h5>
                <hr>
                <p>{!! $post->body !!}</p>
                <hr>
            </div>
            <div class="row postingan-detail">
                <p>{{ $post->created_at->format('j F Y') }} ({{ $post->created_at->diffForHumans() }})
                    {{ $post->hasBeenUpdated() ? '| ' . $post->updated_at->format('j F Y') . ' (Edited)' : '' }}</p>
                <div class="postingan-tag">
                    @foreach ($post->tags as $tag)
                        <a href="{{ route('viewTag', ['id' => $tag->id]) }}">{{ $tag->name }}</a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
