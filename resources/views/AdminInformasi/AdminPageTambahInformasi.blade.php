@extends('layouts.adminlayout')

@section('title', 'Tambah Informasi')

@section('content')
    <div class="admin modern-page">
        <h2 class="modern-page__heading">Form Penambahan Informasi</h2>

        <div class="form row form--wide">
            @include('partials.Alerts')
            <form method="POST" action="{{ route('admin.posts.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="judul" class="form-label">
                        <h4>Judul<span class="required-star">*</span></h4>
                    </label>
                    <input name="title" type="text" class="form-control" id="judul" required>
                </div>
                <div class="mb-3">
                    <label for="subjudul" class="form-label">
                        <h4>Sub-Judul<span class="required-star">*</span></h4>
                    </label>
                    <input name="subtitle" type="text" class="form-control" id="subjudul" required>
                </div>
                <div class="mb-3">
                    <label for="deskripsi" class="form-label">
                        <h4>Deskripsi<span class="required-star">*</span></h4>
                    </label>
                    <textarea name="body" type="text" class="form-control" id="deskripsi"></textarea>
                </div>
                <script>
                    ClassicEditor
                        .create(document.querySelector('#deskripsi'))
                        .catch(error => {
                            console.error(error);
                        });
                </script>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="gambar" class="form-label">
                                <h4>Gambar</h4>
                            </label>
                            <input name="image" type="file" accept=".png, .jpeg, .jpg" class="form-control" id="gambar">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="tag" class="form-label">
                                <h4>Tag<span class="required-star">*</span></h4>
                            </label>
                            <div class="dropdown" onclick="performSearch()">
                                <button class="modern-button modern-button--neutral dropdown-toggle" type="button" id="dropdownMenuButton"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Tag yang dipilih bisa lebih dari 1
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <input type="text" id="searchInput" name="searchInput" placeholder="Cari disini"
                                        autocomplete="off" oninput="performSearch()">
                                    <div id="searchResults">
                                        @foreach ($tags as $tag)
                                            <label class="dropdown-item">
                                                <input name="tags[]" id="{{ $tag->id }}" type="checkbox"
                                                    class="checkbox-option" onclick="saveSelection(this)"
                                                    value="{{ $tag->id }}"> {{ $tag->name }}
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="modern-button modern-button--primary">Submit</button>
                    <a href="{{ route('admin.posts.index') }}" class="modern-button modern-button--soft">Batal</a>
                </div>
            </form>
        </div>
    </div>
    @include('AdminInformasi.TagLiveSearchScript')
@endsection
