@extends('layouts.homelayout')

@section('title', 'Feedback')

@section('content')
    <div class="keluhan">
        <div class="top">
            <a href="{{ route('home') }}"><i class="fa-solid fa-arrow-left fa-lg"></i>Kembali</a>
            <h1>Silahkan Mengisi Formulir yang Disediakan untuk Melakukan Feedback</h1>
            <hr>
        </div>
        <div class="formulir-keluhan">
            <div class="responsive-iframe">
                <iframe src="{{ $feedbackLink->link }}" frameborder="0" allowfullscreen></iframe>
            </div>
        </div>
    </div>
@endsection
