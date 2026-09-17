@extends('layouts.homelayout')

@section('title', 'Link Penting')
@section('description', 'Kumpulan link penting terkait Program Studi Sarjana Informatika Telkom University.')

@section('content')
    <section class="tag-hero">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap mb-3">
                <a href="{{ route('home') }}" class="back-link"><i class="fa-solid fa-arrow-left"></i><img src="{{ asset('images/Logo2.png') }}" alt=""> <span>Kembali</span></a>
            </div>

            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <p class="eyebrow">Informasi</p>
                    <h1>Link Penting</h1>
                    <p class="lead">Kumpulan link penting terkait informasi yang ada di Program Studi S1 Informatika.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="home-content">
        <div class="container-fluid">
            <div class="row g-4">
                <div class="col-xl-9 col-lg-8">
                    <div class="topic-block">
                        @forelse($sections as $section)
                            <div id="section-{{ $section->id }}" class="section-group">
                                <div class="section-header">
                                    <span class="section-number">{{ $loop->iteration }}</span>
                                    <h2>{{ $section->name }}</h2>
                                </div>

                                <div class="link-list">
                                    @forelse($section->important_links as $link)
                                        <a href="{{ $link->link }}" target="_blank" rel="noopener noreferrer" class="resource-link">
                                            <span class="resource-number">{{ $loop->iteration }}</span>
                                            <span class="resource-icon"><i class="fa-solid fa-arrow-up-right-from-square"></i></span>
                                            <span class="resource-copy">
                                            <span class="resource-title">{{ $link->name }}</span>
                                            <span class="resource-url">{{ $link->link }}</span>
                                            </span>
                                        </a>
                                    @empty
                                        @include('partials.Empty')
                                    @endforelse
                                </div>
                            </div>
                        @empty
                            <div class="empty-state">
                                @include('partials.Empty')
                            </div>
                        @endforelse
                    </div>
                </div>

                <aside class="col-xl-3 col-lg-4">
                    <div class="sidebar-box">
                        <h3>Daftar Section</h3>
                        <ul>
                            @forelse($sections as $section)
                                <li><a href="#section-{{ $section->id }}"><span>{{ $loop->iteration }}</span>{{ $section->name }}</a></li>
                            @empty
                                <li>@include('partials.Empty')</li>
                            @endforelse
                        </ul>
                    </div>
                </aside>
            </div>
        </div>
    </section>
@endsection
