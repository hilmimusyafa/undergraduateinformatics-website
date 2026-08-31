<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Admin Dashboard') - S1 Informatika Telkom University</title>

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- CKEditor 5 -->
    <script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>

    <!-- Custom Admin CSS -->
    <link rel="stylesheet" href="/css/styleAdmin.css">

    @yield('extra-css')
</head>

<body class="admin-body">
    <div class="admin-wrapper">
        <!-- Sidebar Navigation -->
        @include('navbars.AdminNavbar')

        <!-- Main Content Area -->
        <div class="admin-main-area">
            <!-- Topbar Header -->
            <header class="admin-topbar">
                <div class="topbar-left">
                    <button class="btn btn-sm btn-outline-secondary d-lg-none" type="button" onclick="document.querySelector('.admin-sidebar').classList.toggle('show')">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <h2 class="topbar-page-title">@yield('title', 'Admin Panel')</h2>
                </div>
                <div class="topbar-right">
                    <a href="{{ route('home') }}" target="_blank" class="btn-topbar-link">
                        <i class="fa-solid fa-arrow-up-right-from-square text-danger"></i>
                        <span>Lihat Website</span>
                    </a>
                    <a href="{{ route('logout') }}" class="btn-topbar-link text-danger border-danger-subtle">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </header>

            <!-- Page Dynamic Content -->
            <main class="admin-content-container">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Bootstrap 5.3 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

    @yield('extra-js')
</body>

</html>
