@extends('layouts.adminlayout')

@section('title', 'List Link Penting')

@section('content')
    <div class="admin col-md-9">
        <div class="table-top">
            <h1>Silahkan Tambah, Ganti atau Hapus Link Penting yang Tersedia</h1>
            <hr>
            @include('partials.Alerts')
            <div class="d-flex">
                <div class="col-md">
                    <a href="{{ route('links.create') }}">
                        <button class="btn btn-secondary">
                            <i class="fa-solid fa-plus"></i> Tambah Link Penting
                        </button>
                    </a>
                </div>
                <div class="col-md-3">
                    <form method="GET" action="{{ route('links.index') }}" class='d-flex'>
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
                        <th scope="col">Nama Section</th>
                        <th scope="col">Deskripsi</th>
                        <th scope="col">Link</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($links as $link)
                        <tr>
                            <td>{{ $link->important_section->name }}</td>
                            <td>{{ $link->name }}</td>
                            <td class="text-truncate">
                                <a href="{{ $link->link }}" target="_blank">
                                    {{ $link->link }}
                                </a>
                            </td>
                            <td class="aksi"><a class="edit"
                                    href="{{ route('links.edit', ['link' => $link->id]) }}">Edit</a>
                                <a class="delete" href="#" data-bs-toggle="modal"
                                    data-bs-target="#confirmModal-{{ $link->id }}">Delete</a>
                            </td>
                        </tr>

                        <div class="modal fade" id="confirmModal-{{ $link->id }}" tabindex="-1"
                            aria-labelledby="confirmModalLabel-{{ $link->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="confirmModalLabel-{{ $link->id }}">
                                            Konfirmasi</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Apakah yakin dihapus?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Batal</button>
                                        <form id="delete-form-{{ $link->id }}"
                                            action="{{ route('links.destroy', ['link' => $link->id]) }}" method="POST">
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
            @if ($links->isEmpty())
                @include('partials.Empty')
            @endif
        </div>
    </div>
@endsection
