@extends('layouts.adminlayout')

@section('title', 'Edit Pertanyaan')

@section('content')
    <div class="admin col-md-9">
        <div class="top">
            <h1>Form Pengeditan Pertanyaan untuk Lupa Password</h1>
        </div>
        <div class="form row">
            @include('partials.alerts')
            <form method="POST" action="{{ route('updatePasswordRecoveryQuestion') }}" class="d-flex">
                @csrf
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="link" class="form-label">
                            <h4>Pertanyaan 1</h4>
                        </label>
                        <textarea type="text" name="first_question" class="form-control" id="link" required>{{ $first_question }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="link" class="form-label">
                            <h4>Pertanyaan 2</h4>
                        </label>
                        <textarea type="text" name="second_question" class="form-control" id="link" required>{{ $second_question }}</textarea>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-danger">Submit</button>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="link" class="form-label">
                            <h4>Jawaban 1</h4>
                        </label>
                        <textarea type="text" name="first_answer" class="form-control" id="link" required>{{ $first_answer }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="link" class="form-label">
                            <h4>Jawaban 2</h4>
                        </label>
                        <textarea type="text" name="second_answer" class="form-control" id="link" required>{{ $second_answer }}</textarea>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
