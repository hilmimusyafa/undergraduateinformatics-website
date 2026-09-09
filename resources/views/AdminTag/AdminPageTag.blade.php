@extends('layouts.adminlayout')

@section('title', 'List Tag')

@section('content')
    <div class="admin col-md-9">
        <div class="table-top">
            <h1>Manajemen Tag Post Informasi</h1>
            <hr>
            @include('partials.Alerts')
            <div class="d-flex">
                <div class="col-md">
                    <a class="modern-button modern-button--soft" href="{{ route('admin.tags.create') }}">
                        <i class="fa-solid fa-plus"></i> Tambah Tag
                    </a>
                </div>
                <div class="col-md-3">
                    <form method="GET" action="{{ route('admin.tags.index') }}" class='d-flex'>
                        <input class="form-control" name="search" type="search" placeholder="Cari"
                            value="{{ request()->get('search') }}" aria-label="Search">
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
                            <td><div class="cell-clamp">{{ Str::limit($tag->description, 100) }}</div></td>
                            {{-- <td><img src="/images/imgCard.svg" alt=""></td> --}}
                            <td class="aksi">
                                <a class="edit" href="{{ route('admin.tags.edit', ['tag' => $tag->id]) }}">Edit</a>
                                <a class="delete" href="#" data-bs-toggle="modal"
                                    data-bs-target="#confirmModal-{{ $tag->id }}">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if ($tags->isEmpty())
                @include('partials.Empty')
            @endif
        </div>

        @foreach ($tags as $tag)
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
                            <button type="button" class="modern-button modern-button--soft"
                                data-bs-dismiss="modal">Batal</button>
                            <form id="delete-form-{{ $tag->id }}"
                                action="{{ route('admin.tags.destroy', ['tag' => $tag->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="modern-button modern-button--danger">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection