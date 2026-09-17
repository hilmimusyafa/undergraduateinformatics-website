@extends('layouts.homelayout')

@section('title', 'Portal Informasi Sarjana Informatika')
@section('description', 'Sumber informasi resmi Program Studi Sarjana Informatika Telkom University')

@section('content')
    <section class="public-hero">
        <div class="container-fluid">
            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <p class="eyebrow">Portal Informasi</p>
                    <h1>Portal Informasi<br> Sarjana Informatika</h1>
                    <p class="lead">Sumber informasi resmi Program Studi Sarjana Informatika Telkom University</p>
                </div>
                <div class="col-lg-5">
                    <div class="hero-image-card">
                        <img src="{{ asset('images/banner.jpg') }}" alt="Telkom University">
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if ($statistics->isNotEmpty())
        <section class="statistics-section" aria-labelledby="statistics-title">
            <div class="container-fluid">
                <div class="statistics-heading">
                    <div>
                        <p class="eyebrow">Data Mahasiswa</p>
                        <h2 id="statistics-title">Statistik Mahasiswa</h2>
                        <p>Visualisasi data mahasiswa yang sama dengan dashboard admin.</p>
                    </div>
                    <span class="statistics-heading__icon"><i class="fa-solid fa-chart-simple"></i></span>
                </div>

                <div class="chart-grid public-chart-grid">
                    @foreach ($statistics as $dataset)
                        <article class="chart-card public-chart-card">
                            <div class="chart-card__head">
                                <h3 class="chart-card__title">{{ $dataset->title }}</h3>
                            </div>
                            <div class="chart-canvas">
                                <canvas id="public-chart-{{ $dataset->id }}"></canvas>
                            </div>
                            @if ($dataset->x_label)
                                <p class="chart-card__axis">{{ $dataset->x_label }}</p>
                            @endif
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="home-content">
        <div class="container-fluid">
            <div class="row g-4">
                <div class="col-xl-9 col-lg-8">
                    @forelse($tags as $data)
                        <div class="topic-block">
                            <div class="topic-header">
                                <a href="{{ route('tags.show', ['slug' => $data->slug]) }}">
                                    <span class="topic-mark"></span>
                                    <h2>{{ $data->name }}</h2>
                                </a>
                                <a href="{{ route('tags.show', ['slug' => $data->slug]) }}" class="view-more">Lihat semua</a>
                            </div>

                            <div class="row g-4">
                                @forelse($data->posts->sortByDesc('updated_at')->take(3) as $post)
                                    <div class="col-md-4">
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
                                        @include('partials.Empty')
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            @include('partials.Empty')
                        </div>
                    @endforelse
                </div>

                <aside class="col-xl-3 col-lg-4">
                    <div class="sidebar-box">
                        <h3>Info Terbaru</h3>
                        <ul>
                            @forelse($posts->take(8) as $post)
                                <li>
                                    <a href="{{ route('posts.show', ['slug' => $post->slug]) }}">{{ $post->title }}</a>
                                </li>
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

@if ($statistics->isNotEmpty())
    @include('AdminDashboard._chart-assets')

    @push('scripts')
        <script>
            const publicDatasets = @json($statisticsPayload);

            publicDatasets.forEach((dataset) => {
                const canvas = document.getElementById(`public-chart-${dataset.id}`);
                if (!canvas) return;

                window.renderChartPreview(
                    canvas,
                    dataset.labels,
                    dataset.values.map((value) => Number(value)),
                    ['bar', 'line', 'pie'].includes(dataset.chart_type?.toLowerCase()) ? dataset.chart_type.toLowerCase() : 'bar'
                );
            });
        </script>
    @endpush
@endif
