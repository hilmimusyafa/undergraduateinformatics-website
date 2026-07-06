@extends('layouts.adminlayout')

@section('title', 'Tambah Tag')

@section('content')
    <div class="admin col-md-9">
        <div class="kembali">
            <a href="{{ route('tags.index') }}">
                <i class="fa-solid fa-arrow-left"></i>Kembali
            </a>
        </div>
        <div class="top">
            <h1>Form Penambahan Tag</h1>
        </div>
        <div class="form row">
            @include('partials.Alerts')
            <form method="POST" action="{{ route('tags.store') }}" enctype="multipart/form-data" class="d-flex">
                @csrf
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="namatag" class="form-label">
                            <h4>Nama Tag</h4>
                        </label>
                        <input name="name" type="text" class="form-control" id="namatag" required>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">
                            <h4>Deskripsi</h4>
                        </label>
                        <textarea name="description" type="text" class="form-control" id="deskripsi" required></textarea>
                    </div>
                    {{-- <div class="mb-3">
                                <label for="gambar" class="form-label">
                                    <h4>Gambar</h4>
                                </label>
                                <input type="file" accept="image/*" class="form-control" id="gambar">
                            </div> --}}
                    <div class="mt-3">
                        <button type="submit" class="btn btn-danger">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
