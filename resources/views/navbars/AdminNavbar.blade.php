<aside class="admin-sidebar">
    <!-- Brand Logo Section -->
    <div class="sidebar-brand-wrapper">
        <a href="{{ route('posts.index') }}" class="d-flex align-items-center gap-2 text-decoration-none">
            <img class="sidebar-logo" src="/images/Logo2.png" alt="Logo S1 Informatika Telkom University" onerror="this.onerror=null; this.src='/images/Logo.png';">
            <div class="sidebar-brand-info">
                <span class="sidebar-brand-title">Admin Portal</span>
                <span class="sidebar-brand-sub">S1 Informatika</span>
            </div>
        </a>
    </div>

    <!-- Navigation Groups -->
    <div class="sidebar-nav-container">
        <!-- Group: Manajemen Konten -->
        <div class="sidebar-nav-group-title">Manajemen Konten</div>
        <ul class="sidebar-nav-list">
            <li class="sidebar-nav-item">
                <a href="{{ route('posts.index') }}" class="sidebar-nav-link {{ Route::is('posts.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-newspaper"></i>
                    <span>Informasi & Berita</span>
                </a>
            </li>
            <li class="sidebar-nav-item">
                <a href="{{ route('tags.index') }}" class="sidebar-nav-link {{ Route::is('tags.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-tags"></i>
                    <span>Kategori / Tag</span>
                </a>
            </li>
            <li class="sidebar-nav-item">
                <a href="{{ route('sections.index') }}" class="sidebar-nav-link {{ Route::is('sections.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-layer-group"></i>
                    <span>Section Link</span>
                </a>
            </li>
            <li class="sidebar-nav-item">
                <a href="{{ route('links.index') }}" class="sidebar-nav-link {{ Route::is('links.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-link"></i>
                    <span>Link Penting</span>
                </a>
            </li>
            <li class="sidebar-nav-item">
                <a href="{{ route('feedback.index') }}" class="sidebar-nav-link {{ Route::is('feedback.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-comments"></i>
                    <span>Feedback & Aspirasi</span>
                </a>
            </li>
        </ul>

        <!-- Group: Pengaturan Akun -->
        <div class="sidebar-nav-group-title">Pengaturan & Keamanan</div>
        <ul class="sidebar-nav-list">
            <li class="sidebar-nav-item">
                <a href="{{ route('forgotPassword') }}" class="sidebar-nav-link">
                    <i class="fa-solid fa-key"></i>
                    <span>Ganti Password</span>
                </a>
            </li>
            <li class="sidebar-nav-item">
                <a href="{{ route('editPasswordRecoveryQuestion') }}" class="sidebar-nav-link {{ Route::is('editPasswordRecoveryQuestion') ? 'active' : '' }}">
                    <i class="fa-solid fa-shield-halved"></i>
                    <span>Pertanyaan Keamanan</span>
                </a>
            </li>
            <li class="sidebar-nav-item">
                <a href="{{ route('home') }}" class="sidebar-nav-link" target="_blank">
                    <i class="fa-solid fa-globe"></i>
                    <span>Kunjungi Portal Publik</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Sidebar User Footer -->
    <div class="sidebar-footer-wrapper">
        <div class="admin-user-profile">
            <div class="user-avatar-circle">
                <i class="fa-solid fa-user-shield"></i>
            </div>
            <div class="user-profile-info">
                <div class="user-profile-name">{{ Auth::user()->email ?? 'Administrator' }}</div>
                <div class="user-profile-role">Admin Prodi S1 IF</div>
            </div>
            <a href="{{ route('logout') }}" class="text-danger p-2" title="Keluar">
                <i class="fa-solid fa-right-from-bracket"></i>
            </a>
        </div>
    </div>
</aside>