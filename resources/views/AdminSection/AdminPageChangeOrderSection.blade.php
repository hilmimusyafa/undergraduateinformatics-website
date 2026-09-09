@extends('layouts.adminlayout')

@section('title', 'Ganti Urutan Section')

@section('content')
    <div class="admin col-md-9">
        <div class="kembali">
            <a href="{{ route('admin.sections.index') }}">
                <i class="fa-solid fa-arrow-left"></i>Kembali
            </a>
        </div>

        <div class="top">
            <h1>Pergantian Urutan Section</h1>
        </div>

        <div class="form row form--wide">
            @include('partials.Alerts')
            <p class="modern-page__desc">Gunakan tombol <i class="fa-solid fa-arrow-up"></i> / <i class="fa-solid fa-arrow-down"></i> untuk mengubah urutan, lalu simpan.</p>
            <form method="POST" action="{{ route('admin.sections.updateOrder') }}" id="section-order-form">
                @csrf
                <div class="section-order-list" id="section-order-list">
                    @foreach ($sections as $index => $section)
                        <div class="section-order-item" data-id="{{ $section->id }}">
                            <span class="section-order-badge">{{ $index + 1 }}</span>
                            <span class="section-order-name">{{ $section->name }}</span>
                            <div class="section-order-actions">
                                <button type="button" class="section-order-btn section-order-btn--up" title="Naikkan" aria-label="Naikkan"><i class="fa-solid fa-arrow-up"></i></button>
                                <button type="button" class="section-order-btn section-order-btn--down" title="Turunkan" aria-label="Turunkan"><i class="fa-solid fa-arrow-down"></i></button>
                            </div>
                            <input type="hidden" name="order[{{ $section->id }}]" value="{{ $index + 1 }}">
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 d-flex gap-2">
                    <button type="submit" id="section-order-submit" class="modern-button modern-button--primary">Simpan Urutan</button>
                    <a href="{{ route('admin.sections.index') }}" class="modern-button modern-button--soft">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        (function () {
            const list = document.getElementById('section-order-list');
            const submitBtn = document.getElementById('section-order-submit');

            function refresh() {
                const items = [...list.querySelectorAll('.section-order-item')];
                items.forEach((item, index) => {
                    item.querySelector('.section-order-badge').textContent = index + 1;
                    item.querySelector('input[type="hidden"]').value = index + 1;
                    item.querySelector('.section-order-btn--up').disabled = index === 0;
                    item.querySelector('.section-order-btn--down').disabled = index === items.length - 1;
                });
                submitBtn.disabled = items.length === 0;
            }

            list.addEventListener('click', (event) => {
                const button = event.target.closest('.section-order-btn');
                if (!button) return;
                const item = button.closest('.section-order-item');
                const items = [...list.querySelectorAll('.section-order-item')];
                const index = items.indexOf(item);
                const isUp = button.classList.contains('section-order-btn--up');
                const target = isUp ? index - 1 : index + 1;
                if (target < 0 || target >= items.length) return;
                if (isUp) {
                    list.insertBefore(item, items[target]);
                } else {
                    list.insertBefore(items[target], item);
                }
                refresh();
            });

            refresh();
        })();
    </script>
@endsection