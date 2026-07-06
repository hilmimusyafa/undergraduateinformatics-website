@extends('layouts.adminlayout')

@section('title', 'Edit ' . $post->title)

@section('content')
    <div class="admin col-md-9">
        <div class="kembali">
            <a href="{{ route('posts.index') }}">
                <i class="fa-solid fa-arrow-left"></i>Kembali
            </a>
        </div>
        <div class="top">
            <h1>Form Pengeditan Informasi</h1>
        </div>
        <div class="form row">
            @include('partials.Alerts')
            <form method="POST" action="{{ route('posts.update', ['post' => $post->id]) }}" enctype="multipart/form-data"
                class="d-flex">
                @method('PUT')
                @csrf
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="judul" class="form-label">
                            <h4>Judul</h4>
                        </label>
                        <input name="title" type="text" class="form-control" id="judul" value="{{ $post->title }}"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="subjudul" class="form-label">
                            <h4>Sub-Judul</h4>
                        </label>
                        <input name="subtitle" type="text" class="form-control" id="subjudul"
                            value="{{ $post->subtitle }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">
                            <h4>Deskripsi</h4>
                        </label>
                        <textarea name="body" type="text" class="form-control" id="deskripsi" required>{{ $post->body }}</textarea>
                    </div>
                    <script>
                        ClassicEditor
                            .create(document.querySelector('#deskripsi'))
                            .catch(error => {
                                console.error(error);
                            });
                    </script>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-danger">Submit</button>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="gambar" class="form-label">
                            <h4>Gambar</h4>
                        </label>
                        @if ($post->hasImage())
                            <div id="image-container" class="mb-3">
                                <img src="/{{ $post->image }}" alt="{{ $post->title }}"
                                    style="max-width:100%; max-height:100%;">
                                <input name="deleteGambar" type="checkbox" id="deleteGambar" onclick="deleteImage()"> Hapus Gambar
                            </div>
                        @endif
                        <input name="image" type="file" accept="image/*" class="form-control" id="gambar">
                    </div>
                    <div class="mb-3">
                        <label for="tag" class="form-label">
                            <h4>Tag</h4>
                        </label>
                        <div class="dropdown" onclick="performSearch()">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
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
                                                value="{{ $tag->id }}"
                                                {{ in_array($tag->id, $post->tags->pluck('id')->toArray()) ? 'checked' : '' }}>
                                            {{ $tag->name }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @include('AdminInformasi.TagLiveSearchScript')
@endsection

<script>
    function deleteImage() {
        var deleteGambar = document.getElementById('deleteGambar');
        var imageContainer = document.getElementById('image-container');
        if (deleteGambar.checked) {
            if (imageContainer) {
                imageContainer.innerHTML = '<input name="deleteGambar" type="checkbox" id="deleteGambar" onclick="deleteImage()" checked> Hapus Gambar';
            }
        } else {
            imageContainer.innerHTML = '<img src="/{{ $post->image }}" alt="{{ $post->title }}" style="max-width:100%; max-height:100%;"> <input name="deleteGambar" type="checkbox" id="deleteGambar" onclick="deleteImage()"> Hapus Gambar';
        }
    }
</script>