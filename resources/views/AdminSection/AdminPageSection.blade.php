@extends('layouts.adminlayout')

@section('title', 'List Section')

@section('content')
    <div class="admin col-md-9">
        <div class="table-top">
            <h1>Manajemen Section Link Penting</h1>
            <hr>
            @include('partials.Alerts')
            <div class="d-flex">
                <div class="col-md">
                    <a class="modern-button modern-button--soft" href="{{ route('admin.sections.create') }}">
                        <i class="fa-solid fa-plus"></i> Tambah Section
                    </a>

                    <a class="modern-button modern-button--soft" href="{{ route('admin.sections.changeOrder') }}">
                        <i class="fa-solid fa-sort"></i> Ganti Urutan Section
                    </a>
                </div>
                <div class="col-md-3">
                    <form method="GET" action="{{ route('admin.sections.index') }}" class='d-flex'>
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
                        <th scope="col">Nama Section</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sections as $section)
                        <tr>
                            <td>{{ $section->name }}</td>
                            <td class="aksi"><a class="edit"
                                    href="{{ route('admin.sections.edit', ['section' => $section->id]) }}">Edit</a>
                                <a class="delete" href="#" data-bs-toggle="modal"
                                    data-bs-target="#confirmModal-{{ $section->id }}">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if ($sections->isEmpty())
                @include('partials.Empty')
            @endif
        </div>

        @foreach ($sections as $section)
            <div class="modal fade" id="confirmModal-{{ $section->id }}" tabindex="-1"
                aria-labelledby="confirmModalLabel-{{ $section->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirmModalLabel-{{ $section->id }}">Konfirmasi</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Apakah yakin dihapus?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="modern-button modern-button--soft"
                                data-bs-dismiss="modal">Batal</button>
                            <form id="delete-form-{{ $section->id }}"
                                action="{{ route('admin.sections.destroy', ['section' => $section->id]) }}"
                                method="POST">
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