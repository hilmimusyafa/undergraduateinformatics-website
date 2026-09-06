<nav class="navbar navbar-expand-lg sticky-top drop-shadow">
    <div class="container-fluid">
        <a href="{{ route('home') }}"><img class="img-fluid" src="/images/Logo2.png" alt="logoProdi"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="nav ms-auto nav-underline">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" aria-current="page" href="#" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Informasi
                    </a>
                    <ul class="dropdown-menu">
                        @foreach ($tags_navbar->slice(0, 8) as $tag)
                            <li><a class="dropdown-item"
                                    href="{{ route('viewTag', ['slug' => $tag->slug]) }}">{{ $tag->name }}</a></li>
                        @endforeach
                        <li><a class="dropdown-item" href="{{ route('posts.search') }}">Informasi Lainnya</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home.links') }}">Link Penting</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('viewFeedback') }}">Masukan/Saran</a>
                </li>
                <li class="nav-item">
                    <form method="GET" action="{{ route('posts.search') }}" class="d-flex" role="search">
                        <input class="form-control me-2" name="search" type="search" placeholder="Cari"
                            aria-label="Search">
                    </form>
                </li>
                <li class="nav-item">
                    <a href="{{ Auth::check() ? route('posts.index') : route('login') }}" class="btn-login">
                        <div class="btn btn-light text-center">Admin</div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fa-solid fa-language"></i>ID</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
