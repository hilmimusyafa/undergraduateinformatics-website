@extends('layouts.adminlayout')

@section('title', 'Tambah Link Penting')

@section('content')
    <div class="admin col-md-9">
        <div class="kembali">
            <a href="{{ route('links.index') }}">
                <i class="fa-solid fa-arrow-left"></i>Kembali
            </a>
        </div>
        <div class="top">
            <h1>Form Penambahan Link Penting</h1>
        </div>
        <div class="form row">
            @include('partials.Alerts')
            <form method="POST" action="{{ route('links.store') }}" class="d-flex">
                @csrf
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="tag" class="form-label">
                            <h4>Pilih Section</h4>
                        </label>
                        <select class="form-select" aria-label="Default select example" name="section_id">
                            @foreach ($sections as $section)
                                <option value="{{ $section->id }}">{{ $section->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">
                            <h4>Deskripsi</h4>
                        </label>
                        <input name="name" type="text" class="form-control" id="deskripsi" required>
                    </div>
                    <div class="mb-3">
                        <label for="link" class="form-label">
                            <h4>Link</h4>
                        </label>
                        <textarea name="link" type="text" class="form-control" id="link" required></textarea>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-danger">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
