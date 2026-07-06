@extends('layouts.adminlayout')

@section('title', 'Tambah Section')

@section('content')
    <div class="admin col-md-9">
        <div class="kembali">
            <a href="{{ route('sections.index') }}">
                <i class="fa-solid fa-arrow-left"></i>Kembali
            </a>
        </div>
        <div class="top">
            <h1>Form Penambahan Section</h1>
        </div>
        <div class="form row">
            @include('partials.Alerts')
            <form method="POST" action="{{ route('sections.store') }}" class="d-flex">
                @csrf
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="namasection" class="form-label">
                            <h4>Nama Section</h4>
                        </label>
                        <input name="name" type="text" class="form-control" id="namasection" required>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-danger">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
