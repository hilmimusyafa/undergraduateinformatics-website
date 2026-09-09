@extends('layouts.adminlayout')

@section('title', 'List Informasi')

@section('content')
    <div class="admin col-md-9">
        <div class="table-top">
            <div class="posts-toolbar">
                <h1>Manajemen Informasi</h1>
                <div class="d-flex">
                    <div class="col-md">
                        <a class="modern-button modern-button--soft" href="{{ route('admin.posts.create') }}">
                            <i class="fa-solid fa-plus"></i> Tambah Informasi
                        </a>
                    </div>
                    <div class="col-md-3">
                        <form method="GET" action="{{ route('admin.posts.index') }}" class='d-flex'>
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
                    @foreach ($posts as $data)
                        <tr>
                            <td><div class="cell-clamp">{{ Str::limit($data->title, 100) }}</div></td>
                            <td><div class="cell-clamp">{{ Str::limit($data->subtitle, 100) }}</div></td>
                            <td><div class="cell-clamp">{{ Str::limit(strip_tags($data->body), 120) }}</div></td>
                            <td><img src="/{{ $data->image }}" alt="{{ $data->title }}"></td>
                            <td>
                                <div class="tag-list">
                                    @foreach ($data->tags as $post_tags)
                                        <a class="tag-pill" href="{{ route('tags.show', ['slug' => $post_tags->slug]) }}">{{ $post_tags->name }}</a>
                                    @endforeach
                                </div>
                            </td>
                            <td class="aksi"><a class="edit"
                                    href="{{ route('admin.posts.edit', ['post' => $data]) }}">Edit</a>
                                <a class="delete" href="#" data-bs-toggle="modal"
                                    data-bs-target="#confirmModal-{{ $data->id }}">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if ($posts->isEmpty())
                @include('partials.Empty')
            @endif
        </div>

        @foreach ($posts as $data)
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
                            <button type="button" class="modern-button modern-button--soft"
                                data-bs-dismiss="modal">Batal</button>
                            <form id="delete-form-{{ $data->id }}"
                                action="{{ route('admin.posts.destroy', ['post' => $data->id]) }}" method="POST">
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