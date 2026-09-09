@extends('layouts.adminlayout')

@section('title', 'Edit ' . $section->name)

@section('content')
    <div class="admin col-md-9">
        <div class="kembali">
            <a href="{{ route('admin.sections.index') }}">
                <i class="fa-solid fa-arrow-left"></i>Kembali
            </a>
        </div>
        <div class="top">
            <h1>Form Pengeditan Section</h1>
        </div>
        <div class="form row">
            @include('partials.Alerts')
            <form method="POST" action="{{ route('admin.sections.update', ['section' => $section->id]) }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="namasection" class="form-label">
                        <h4>Nama Section</h4>
                    </label>
                    <input name="name" type="text" class="form-control" id="namasection"
                        value="{{ $section->name }}" required>
                </div>

                <div class="mt-3">
                    <button type="submit" class="modern-button modern-button--primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
@endsection
