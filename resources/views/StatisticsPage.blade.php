@extends('layouts.homelayout')

@section('title', 'Statistik Mahasiswa')
@section('description', 'Visualisasi data dan rekap statistik mahasiswa Program Studi Sarjana Informatika Telkom University.')

@section('content')
    <section class="tag-hero public-intro-hero">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap mb-3">
                <a href="{{ route('home') }}" class="back-link"><i class="fa-solid fa-arrow-left"></i><img src="{{ asset('images/Logo2.png') }}" alt=""> <span>Kembali</span></a>
            </div>

            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <p class="eyebrow">Data & Informasi</p>
                    <h1>Statistik Mahasiswa</h1>
                    <p class="lead">Visualisasi grafik dan rekap data statistik mahasiswa Program Studi Sarjana Informatika Telkom University.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="home-content py-4">
        <div class="container-fluid">
            @if ($statistics->isEmpty())
                <div class="topic-block text-center py-5">
                    <i class="fa-solid fa-chart-simple fa-3x text-muted mb-3"></i>
                    <h3>Belum Ada Data Statistik</h3>
                    <p class="text-muted">Data statistik mahasiswa belum dipublikasikan.</p>
                </div>
            @else
                <div class="d-flex flex-column gap-4">
                    @foreach ($statistics as $dataset)
                        <div class="topic-block p-4 bg-white rounded-3 shadow-sm border mb-0">
                            <div class="topic-header border-bottom pb-3 mb-4">
                                <div class="topic-header__title-wrap">
                                    <h2 class="h4 mb-0 text-dark fw-bold">{{ $dataset->title }}</h2>
                                </div>
                            </div>

                            <div class="row g-4 align-items-center">
                                {{-- GRAFIK / CHART (KOLOM KIRI) --}}
                                <div class="col-lg-7">
                                    <div class="p-3 bg-light rounded-3 border" style="min-height: 320px; position: relative;">
                                        <div style="height: 300px; width: 100%; position: relative;">
                                            <canvas id="stat-chart-{{ $dataset->id }}"></canvas>
                                        </div>
                                    </div>
                                    @if ($dataset->x_label)
                                        <p class="text-center text-muted small fw-semibold mt-2 mb-0">{{ $dataset->x_label }}</p>
                                    @endif
                                </div>

                                {{-- TABEL DATA (KOLOM KANAN) --}}
                                <div class="col-lg-5">
                                    <div class="table-responsive rounded-3 border">
                                        <table class="table table-striped table-hover mb-0 align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="ps-3 py-2">{{ $dataset->x_label ?: 'Keterangan / Label' }}</th>
                                                    <th class="pe-3 py-2 text-end">{{ $dataset->y_label ?: 'Jumlah' }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($dataset->items as $item)
                                                    <tr>
                                                        <td class="ps-3 py-2">{{ $item->label }}</td>
                                                        <td class="pe-3 py-2 text-end fw-semibold text-danger">
                                                            {{ is_numeric($item->value) && floor($item->value) == $item->value ? number_format($item->value, 0, ',', '.') : $item->value }}
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="2" class="text-center text-muted py-3">Tidak ada item data.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                            @if ($dataset->items->isNotEmpty() && $dataset->chart_type !== 'line')
                                                <tfoot class="table-group-divider fw-bold bg-white">
                                                    <tr>
                                                        <td class="ps-3 py-2">Total</td>
                                                        <td class="pe-3 py-2 text-end text-dark">
                                                            {{ number_format($dataset->items->sum('value'), 0, ',', '.') }}
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            @endif
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection

@if ($statistics->isNotEmpty())
    @include('AdminDashboard._chart-assets')

    @push('scripts')
        <script>
            const publicDatasets = @json($statisticsPayload);

            publicDatasets.forEach((dataset) => {
                const canvas = document.getElementById(`stat-chart-${dataset.id}`);
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
