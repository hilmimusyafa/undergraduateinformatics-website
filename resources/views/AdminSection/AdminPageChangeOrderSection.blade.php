@extends('layouts.adminlayout')

@section('title', 'Ganti Urutan Section')

@section('content')
    <div class="admin col-md-9">
        <div class="kembali">
            <a href="{{ route('sections.index') }}">
                <i class="fa-solid fa-arrow-left"></i>Kembali
            </a>
        </div>

        <div class="top">
            <h1>Pergantian Urutan Section</h1>
        </div>

        <div class="form row">
            @include('partials.Alerts')
            <form method="POST" action="{{ route('sections.updateOrder') }}" class="d-flex">
                @csrf
                <div class="container">
                    <div class="row fw-bold mb-2">
                        <div class="col-6">Nama Section</div>
                        <div class="col-6">Urutan Section</div>
                    </div>

                    @foreach ($sections as $section)
                        <div class="row mb-2">
                            <div class="col-5">
                                {{ $section->name }}
                            </div>
                            <div class="col-3">
                                <input type="number"
                                    name="order[{{ $section->id }}]"
                                    class="form-control order-input"
                                    placeholder="Enter order"
                                    min="1">
                            </div>
                        </div>
                    @endforeach

                    <div class="row mt-3">
                        <div class="col-2">
                            <button type="button" id="generateOrder" class="btn btn-warning">
                                Isi Otomatis Urutan
                            </button>
                        </div>
                        <div class="col-1">
                            <button type="submit" id="submitBtn" class="btn btn-danger" disabled>
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <script>
        const inputs = [...document.querySelectorAll('.order-input')];
        const submitBtn = document.getElementById('submitBtn');
        const generateBtn = document.getElementById('generateOrder');
        const total = inputs.length;

        function validateInputs() {
            let values = inputs.map(input => input.value.trim());
            let filled = values.every(val => val !== "");
            let valid = true;

            // reset invalid state
            inputs.forEach(input => input.classList.remove('is-invalid'));

            // duplicate check
            let seen = new Set();
            values.forEach((val, idx) => {
                if (val !== "") {
                    const num = parseInt(val, 10);

                    if (isNaN(num)) {
                        valid = false;
                        inputs[idx].classList.add('is-invalid');
                    }
                    else if (num > total || num < 1) {
                        valid = false;
                        inputs[idx].classList.add('is-invalid');
                    }
                    else if (seen.has(num)) {
                        valid = false;
                        inputs[idx].classList.add('is-invalid');
                    }
                    seen.add(num);
                }
            });

            submitBtn.disabled = !(filled && valid);
        }

        inputs.forEach(input => {
            input.addEventListener('input', validateInputs);
        });

        generateBtn.addEventListener('click', function () {
            let used = new Set();
            inputs.forEach(input => {
                if (input.value) {
                    used.add(parseInt(input.value));
                }
            });

            let available = [];
            for (let i = 1; i <= total; i++) {
                if (!used.has(i)) {
                    available.push(i);
                }
            }

            inputs.forEach(input => {
                if (!input.value) {
                    input.value = available.shift();
                }
            });

            validateInputs();
        });

        validateInputs();
    </script>


@endsection