@extends('layouts.authlayout')

@section('title', 'Ganti Password')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="text-center">Lupa Password</h3>
        </div>
        <div class="card-body">
            <div class="back">
                <a href="{{ route('forgotPassword') }}"><i class="fa-solid fa-arrow-left fa-lg"></i>Kembali</a>
            </div>
            <form method="POST" action="{{ route('submitAnswerRecovery') }}">
                @csrf
                <div class="pertanyaan-field">
                    <label for="pertanyaan1" class="form-label">{{ $first_question }}</label>
                    <input type="text" class="form-control" name="first_answer" id="pertanyaan1"
                        placeholder="Masukkan Jawaban" required>
                </div>
                <div class="pertanyaan-field">
                    <label for="pertanyaan1" class="form-label">{{ $second_question }}</label>
                    <input type="text" class="form-control" name="second_answer" id="pertanyaan2"
                        placeholder="Masukkan Jawaban" required>
                </div>
                <div class="bawah d-flex justify-content-end">
                    <input type="hidden" name="user_id" value="{{ $user_id }}" placeholder="Masukkan Jawaban">
                    <button type="submit" class="btn btn-danger">Submit</button>
                </div>
            </form>
        </div>
    </div>
@endsection
