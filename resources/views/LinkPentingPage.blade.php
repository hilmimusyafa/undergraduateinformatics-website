@extends('layouts.homelayout')

@section('title', 'Link Penting')

@section('content')
    <div class="home">
        <div class="top">
            <h1>Kumpulan Link Penting Terkait Informasi yang ada di Prodi S1 Informatika</h1>
            <hr>
        </div>

        <div class="row">
            <div class="col-md-9">
                <div class="section-title">
                    <ol>
                        @foreach ($sections as $section)
                            <div id="{{ $section->id }}" class="section-content">
                                <h3>
                                    <li>
                                        <p>
                                            {{ $section->name }}
                                        </p>
                                    </li>
                                </h3>
                                <ol>
                                    <h5>
                                        @forelse($section->important_links->sortByDesc('name') as $link)
                                            <li>
                                                <div class="section-link d-flex">
                                                    <p>{{ $link->name }}: <a href="{{ $link->link }}"
                                                            target="_blank">{{ $link->link }}</a></p>
                                                </div>
                                            </li>
                                        @empty
                                            @include('partials.Empty')
                                        @endforelse
                                    </h5>
                                </ol>
                            </div>
                        @endforeach
                    </ol>
                    @if ($sections->isEmpty())
                        @include('partials.Empty')
                    @endif
                </div>
            </div>
            <div class="col-md-3">
                <div class="section-title d-flex justify-content-center">
                    <h3>List Section Terbaru</h3>
                </div>
                <div class="section-info col-md">
                    <ol class="d-flex">
                        <h5>
                            @foreach ($sections->sortByDesc('updated_at') as $section)
                                <li><a href="#{{ $section->id }}">{{ $section->name }}</a></li>
                            @endforeach
                        </h5>
                    </ol>
                    @if ($sections->isEmpty())
                        @include('partials.Empty')
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
