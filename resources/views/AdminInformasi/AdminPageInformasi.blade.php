@extends('layouts.adminlayout')

@section('title', 'List Informasi')

@section('content')
    <div class="admin col-md-9">
        <div class="table-top">
            <div class="posts-toolbar">
                <h1>Manajemen Informasi</h1>
                <div class="d-flex">
                    <div class="col-md">
                        <a href="{{ route('posts.create') }}">
                            <button class="btn btn-secondary">
                                <i class="fa-solid fa-plus"></i> Tambah Informasi
                            </button>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <form method="GET" action="{{ route('posts.index') }}" class='d-flex'>
                            <input class="form-control" name="search" type="search" placeholder="Cari"
                                value="{{ request()->get('search') }}" aria-label="Search">
                        </form>
                    </div>
                </div>
            </div>
            <hr>
            @include('partials.Alerts')
        </div>
        <div class="table-admin">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Judul</th>
                        <th scope="col">Sub-Judul</th>
                        <th scope="col">Deskripsi</th>
                        <th scope="col">Gambar</th>
                        <th scope="col">Tag</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($posts as $key => $data)
                        <tr>
                            <td>{{ Str::limit($data->title, 100) }}</td>
                            <td>{{ Str::limit($data->subtitle, 100) }}</td>
                            <td>{!! Str::limit($data->body, 100) !!}</td>
                            <td><img src="/{{ $data->image }}" alt="{{ $data->title }}"></td>
                            <td>
                                <ol>
                                    @foreach ($data->tags as $key => $post_tags)
                                        <a href="{{ route('viewTag', ['id' => $post_tags->id]) }}">
                                            <li>
                                                {{ $post_tags->name }}
                                            </li>
                                        </a>
                                    @endforeach
                                </ol>
                            </td>
                            <td class="aksi"><a class="edit"
                                    href="{{ route('posts.edit', ['post' => $data]) }}">Edit</a>
                                <a class="delete" href="#" data-bs-toggle="modal"
                                    data-bs-target="#confirmModal-{{ $data->id }}">Delete</a>
                            </td>
                        </tr>

                        <div class="modal fade" id="confirmModal-{{ $data->id }}" tabindex="-1"
                            aria-labelledby="confirmModalLabel-{{ $data->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="confirmModalLabel-{{ $data->id }}">Konfirmasi</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Apakah yakin dihapus?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Batal</button>
                                        <form id="delete-form-{{ $data->id }}"
                                            action="{{ route('posts.destroy', ['post' => $data->id]) }}" method="POST">
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
            @if ($posts->isEmpty())
                @include('partials.Empty')
            @endif
        </div>
    </div>
@endsection
