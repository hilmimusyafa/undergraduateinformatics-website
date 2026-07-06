@extends('layouts.adminlayout')

@section('title', 'Feedback')

@section('content')
    <div class="admin col-md-9">
        <div class="table-top">
            <h1>Silahkan Ganti Link Feedback</h1>
            <hr>
        </div>
        <div class="table-admin">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Link Feedback</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @include('partials.Alerts')
                    <tr>
                        <td>
                            <a href="{{ $feedbackLink->link }}">
                                {{ $feedbackLink->link }}
                            </a>
                        </td>
                        <td class="aksi"><a class="edit" href="{{ route('feedback.edit', ['feedback' => 1]) }}">Edit</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
