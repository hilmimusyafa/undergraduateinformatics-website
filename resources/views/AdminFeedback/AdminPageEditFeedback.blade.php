@extends('layouts.adminlayout')

@section('title', 'Edit Feedback')

@section('content')
    <div class="admin col-md-9">
        <div class="kembali">
            <a href="{{ route('feedback.index') }}">
                <i class="fa-solid fa-arrow-left"></i>Kembali
            </a>
        </div>
        <div class="top">
            <h1>Form Pengeditan Link Feedback</h1>
        </div>
        <div class="form row">
            @include('partials.Alerts')
            <form method="POST" action="{{ route('feedback.update', ['feedback' => 1]) }}">
                @csrf
                @method('PUT')
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="link" class="form-label">
                            <h4>Link</h4>
                        </label>
                        <textarea type="text" class="form-control" name="new_feedback_link" id="link" required>{{ $feedbackLink->link }}</textarea>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-danger">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
