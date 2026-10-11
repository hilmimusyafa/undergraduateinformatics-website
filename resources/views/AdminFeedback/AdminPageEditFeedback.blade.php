@extends('layouts.adminlayout')

@section('title', 'Edit Feedback')

@section('content')
    <div class="admin modern-page">
        <h2 class="modern-page__heading">Form Pengeditan Link Feedback</h2>
        <div class="form row">
            @include('partials.Alerts')
            <form method="POST" action="{{ route('feedback.update', ['feedback' => 1]) }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="link" class="form-label">
                        <h4>Link<span class="required-star">*</span></h4>
                    </label>
                    <textarea type="text" class="form-control" name="new_feedback_link" id="link" required>{{ $feedbackLink->link }}</textarea>
                </div>
                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="modern-button modern-button--primary">Submit</button>
                    <a href="{{ route('admin.form-link') }}" class="modern-button modern-button--soft">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
