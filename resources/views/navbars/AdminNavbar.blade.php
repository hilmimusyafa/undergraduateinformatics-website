<aside class="admin-sidebar">
    <div class="admin-brand"></div>

    <nav class="admin-nav" aria-label="Navigasi admin">
        <p class="admin-nav__label">PENGATURAN UMUM</p>
        <a class="admin-nav__link {{ Route::is('admin.dashboard*') ? 'is-active' : '' }}" href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-chart-line"></i><span>Statistik Mahasiswa</span></a>
        <a class="admin-nav__link {{ Route::is('admin.form-link*') ? 'is-active' : '' }}" href="{{ route('admin.form-link') }}"><i class="fa-solid fa-link"></i><span>Manajemen Form Link</span></a>
        <a class="admin-nav__link {{ Route::is('admin.reservation*') ? 'is-active' : '' }}" href="{{ route('admin.reservation') }}"><i class="fa-solid fa-calendar-check"></i><span>Approval Reservasi</span></a>

        <p class="admin-nav__label">PENGATURAN POST INFORMASI</p>
        <a class="admin-nav__link {{ Route::is('admin.posts*') ? 'is-active' : '' }}" href="{{ route('admin.posts.index') }}"><i class="fa-solid fa-file-lines"></i><span>Manajemen Informasi</span></a>
        <a class="admin-nav__link {{ Route::is('admin.tags*') ? 'is-active' : '' }}" href="{{ route('admin.tags.index') }}"><i class="fa-solid fa-tag"></i><span>Tag Post Informasi</span></a>

        <p class="admin-nav__label">PENGATURAN LINK PENTING</p>
        <a class="admin-nav__link {{ Route::is('admin.sections*') ? 'is-active' : '' }}" href="{{ route('admin.sections.index') }}"><i class="fa-solid fa-list"></i><span>Section Link Penting</span></a>
        <a class="admin-nav__link {{ Route::is('admin.links*') ? 'is-active' : '' }}" href="{{ route('admin.links.index') }}"><i class="fa-solid fa-link"></i><span>Manajemen Link Penting</span></a>
    </nav>

    <div class="admin-sidebar__footer">
        <a class="admin-nav__link" href="{{ route('home') }}"><i class="fa-solid fa-arrow-left"></i><span>Kembali ke website</span></a>
        <a class="admin-nav__link" href="{{ route('admin.logout') }}"><i class="fa-solid fa-arrow-right-from-bracket"></i><span>Logout</span></a>
    </div>
</aside>
