<nav class="navbar navbar-expand-lg main-navbar sticky-top">
    <div class="container">
        <!-- Logo & Branding -->
        <a class="navbar-brand" href="{{ route('home') }}">
            <img class="brand-logo" src="/images/Logo2.png" alt="Logo S1 Informatika Telkom University" onerror="this.onerror=null; this.src='/images/Logo.png';">
            <div class="brand-text d-none d-sm-flex">
                <span class="brand-title">S1 Informatika</span>
                <span class="brand-subtitle">Telkom University</span>
            </div>
        </a>

        <!-- Mobile Toggler Button -->
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbarNav"
            aria-controls="mainNavbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fa-solid fa-bars-staggered fs-4 text-dark"></i>
        </button>

        <!-- Navbar Links -->
        <div class="collapse navbar-collapse" id="mainNavbarNav">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1 my-3 my-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        <i class="fa-solid fa-house"></i>
                        <span>Beranda</span>
                    </a>
                </li>
                
                <!-- Dropdown Kategori / Tags -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ Route::is('viewTag') ? 'active' : '' }}" href="#" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-tags"></i>
                        <span>Kategori</span>
                    </a>
                    <ul class="dropdown-menu shadow-lg border-0">
                        @if(isset($tags_navbar) && $tags_navbar->isNotEmpty())
                            @foreach ($tags_navbar->slice(0, 10) as $tag)
                                <li>
                                    <a class="dropdown-item d-flex align-items-center justify-content-between" href="{{ route('viewTag', ['id' => $tag->id]) }}">
                                        <span>{{ $tag->name }}</span>
                                        <i class="fa-solid fa-chevron-right text-muted" style="font-size: 10px;"></i>
                                    </a>
                                </li>
                            @endforeach
                            <li><hr class="dropdown-divider my-1"></li>
                        @endif
                        <li>
                            <a class="dropdown-item text-primary fw-semibold" href="{{ route('posts.search') }}">
                                <i class="fa-solid fa-layer-group me-1"></i> Semua Kategori & Pencarian
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ Route::is('home.links') ? 'active' : '' }}" href="{{ route('home.links') }}">
                        <i class="fa-solid fa-link"></i>
                        <span>Link Penting</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ Route::is('viewFeedback') ? 'active' : '' }}" href="{{ route('viewFeedback') }}">
                        <i class="fa-solid fa-comment-dots"></i>
                        <span>Masukan/Saran</span>
                    </a>
                </li>

                <!-- Search Input Bar -->
                <li class="nav-item ms-lg-2 my-2 my-lg-0">
                    <form method="GET" action="{{ route('posts.search') }}" class="nav-search-form" role="search">
                        <i class="fa-solid fa-magnifying-glass nav-search-icon"></i>
                        <input class="nav-search-input" name="search" type="search" placeholder="Cari informasi..."
                            value="{{ request()->get('search') }}" aria-label="Search">
                    </form>
                </li>

                <!-- Admin Action Button -->
                <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                    <a href="{{ Auth::check() ? route('posts.index') : route('login') }}" class="btn-admin-nav">
                        <i class="fa-solid fa-user-shield"></i>
                        <span>{{ Auth::check() ? 'Dashboard' : 'Admin Portal' }}</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
