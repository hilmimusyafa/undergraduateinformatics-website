@extends('layouts.adminlayout')

@section('title', 'Feedback')

@section('content')
    <div class="admin modern-page">
        <h2 class="modern-page__heading">Manajemen Link Feedback</h2>
        @include('partials.Alerts')
        <div class="table-admin">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Link Feedback</th>
                        <th scope="col" class="text-end">Aksi</th>
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
                        <td class="aksi"><a class="edit" href="{{ route('feedback.edit', ['feedback' => 1]) }}" title="Edit" aria-label="Edit"><i class="fa-solid fa-pen"></i></a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
