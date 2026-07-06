<div class="side-bar col-md-3 sticky-top">
    <div class="d-flex">
        <a href="{{ route('home') }}"><img class="img-fluid logo" src="/images/Logo2.png" alt="LogoSideBar"></a>
    </div>
    <div class="atas">
        <ul>
            <div class="side-items">
                <a href="{{ route('home') }}">
                    <li><i class="fa-solid fa-arrow-left"></i>Kembali</li>
                </a>
            </div>
        </ul>
    </div>
    <div class="tengah">
        <ul>
            <div class="side-items {{ Route::is('posts.index', 'posts.create', 'posts.edit') ? 'active' : '' }}">
                <a href="{{ route('posts.index') }}">
                    <li><i class="fa-solid fa-circle-info"></i>Informasi</li>
                </a>
            </div>
            <div class="side-items {{ Route::is('tags.index', 'tags.create', 'tags.edit') ? 'active' : '' }}">
                <a href="{{ route('tags.index') }}">
                    <li><i class="fa-solid fa-tag"></i>Tag</li>
                </a>
            </div>
            <div class="side-items {{ Route::is('sections.index', 'sections.create', 'sections.edit') ? 'active' : '' }}">
                <a href="{{ route('sections.index') }}">
                    <li><i class="fa-solid fa-list"></i></i>Section Link</li>
                </a>
            </div>
            <div class="side-items {{ Route::is('links.index', 'links.create', 'links.edit') ? 'active' : '' }}">
                <a href="{{ route('links.index') }}">
                    <li><i class="fa-solid fa-link"></i>Link Penting</li>
                </a>
            </div>
            <div class="side-items {{ Route::is('feedback.index', 'feedback.edit') ? 'active' : '' }}">
                <a href="{{ route('feedback.index') }}">
                    <li><i class="fa-solid fa-comment"></i>Feedback</li>
                </a>
            </div>
            <!-- <div class="side-items {{ Route::is('bookmark') ? 'active' : '' }}">
                <a href="#">
                    <li><i class="fa-solid fa-bookmark"></i>Bookmark</li>
                </a>
            </div> -->
        </ul>
    </div>
    <div class="bawah">
        <ul>
            <div class="side-items">
                <a href="{{ route('forgotPassword') }}">
                    <li><i class="fa-solid fa-key"></i>Ganti Password</li>
                </a>
            </div>
            <div class="side-items {{ Route::is('editPasswordRecoveryQuestion') ? 'active' : '' }}">
                <a href="{{ route('editPasswordRecoveryQuestion') }}">
                    <li><i class="fa-solid fa-question"></i>Ganti Pertanyaan</li>
                </a>
            </div>
            <div class="side-items">
                <a href="{{ route('logout') }}">
                    <li><i class="fa-solid fa-arrow-right-from-bracket"></i>Logout</li>
                </a>
            </div>
        </ul>
    </div>
</div>