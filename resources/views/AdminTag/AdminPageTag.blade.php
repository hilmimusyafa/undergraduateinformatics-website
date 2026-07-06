@extends('layouts.adminlayout')

@section('title', 'List Tag')

@section('content')
    <div class="admin col-md-9">
        <div class="table-top">
            <h1>Silahkan Tambah, Ganti atau Hapus Tag yang Tersedia</h1>
            <hr>
            @include('partials.Alerts')
            <div class="d-flex">
                <div class="col-md">
                    <a href="{{ route('tags.create') }}">
                        <button class="btn btn-secondary">
                            <i class="fa-solid fa-plus"></i> Tambah Tag
                        </button>
                    </a>
                </div>
                <div class="col-md-3">
                    <form method="GET" action="{{ route('tags.index') }}" class='d-flex'>
                        <input class="form-control" name="search" type="search" placeholder="Cari"
                            value="{{ request()->get('search') }}" aria-label="Search">
                        {{-- <a href="#">
                            <button class="btn btn-secondary">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                        </a> --}}
                    </form>
                </div>
            </div>
        </div>
        <div class="table-admin">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Nama Tag</th>
                        <th scope="col">Deskripsi</th>
                        {{-- <th scope="col">Gambar</th> --}}
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tags as $tag)
                        <tr>
                            <td>{{ $tag->name }}</td>
                            <td>{{ Str::limit($tag->description, 100) }}</td>
                            {{-- <td><img src="/images/imgCard.svg" alt=""></td> --}}
                            <td class="aksi">
                                @if ($tag->name == 'S1 Informatika')
                                    <a class="edit" href="{{ route('tags.edit', ['tag' => $tag->id]) }}">Edit Tag
                                        Description</a>
                                @else()
                                    <a class="edit" href="{{ route('tags.edit', ['tag' => $tag->id]) }}">Edit</a>
                                    <a class="delete" href="#" data-bs-toggle="modal"
                                        data-bs-target="#confirmModal-{{ $tag->id }}">Delete</a>
                                @endif
                            </td>
                        </tr>
                        <div class="modal fade" id="confirmModal-{{ $tag->id }}" tabindex="-1"
                            aria-labelledby="confirmModalLabel-{{ $tag->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="confirmModalLabel-{{ $tag->id }}">Konfirmasi</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Apakah yakin dihapus?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Batal</button>
                                        <form id="delete-form-{{ $tag->id }}"
                                            action="{{ route('tags.destroy', ['tag' => $tag->id]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
            @if ($tags->isEmpty())
                @include('partials.Empty')
            @endif
        </div>
    </div>
@endsection
