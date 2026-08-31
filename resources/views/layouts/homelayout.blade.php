<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <title>@yield('title', 'Beranda') - Portal Informasi S1 Informatika Telkom University</title>
    
    <!-- Primary Meta Tags -->
    <meta name="title" content="@yield('title', 'Portal Informasi S1 Informatika')">
    <meta name="description" content="Pusat informasi resmi Program Studi S1 Informatika Telkom University mengenai akademik, MBKM, perkuliahan, dan pengumuman terkini.">
    
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    
    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom Portal CSS -->
    <link rel="stylesheet" href="/css/style.css">
    
    @yield('extra-css')
</head>

<body>
    <!-- Navbar Header -->
    @include('navbars.HomeNavbar')

    <!-- Main Content Body -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="container">
            <div class="row g-4">
                <!-- University & Prodi Info -->
                <div class="col-lg-4 col-md-6">
                    <img src="/images/Logo2.png" alt="Logo Telkom University" class="footer-brand-logo" onerror="this.onerror=null; this.src='/images/Logo.png';">
                    <h5 class="text-white fw-bold mb-2">Program Studi S1 Informatika</h5>
                    <p class="text-muted small mb-3">
                        Fakultas Informatika (School of Computing)<br>
                        Telkom University, Bandung, Jawa Barat.
                    </p>
                    <p class="small text-muted mb-0">
                        Sumber informasi resmi perkuliahan, registrasi mata kuliah, MBKM, tugas akhir, dan kemahasiswaan.
                    </p>
                </div>

                <!-- Tautan Cepat -->
                <div class="col-lg-2 col-md-6 col-6">
                    <h6 class="footer-title">Navigasi</h6>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}"><i class="fa-solid fa-angle-right me-1 small"></i> Beranda</a></li>
                        <li><a href="{{ route('home.links') }}"><i class="fa-solid fa-angle-right me-1 small"></i> Link Penting</a></li>
                        <li><a href="{{ route('posts.search') }}"><i class="fa-solid fa-angle-right me-1 small"></i> Pencarian</a></li>
                        <li><a href="{{ route('viewFeedback') }}"><i class="fa-solid fa-angle-right me-1 small"></i> Masukan / Saran</a></li>
                        <li><a href="{{ route('login') }}"><i class="fa-solid fa-angle-right me-1 small"></i> Login Admin</a></li>
                    </ul>
                </div>

                <!-- Layanan Kampus -->
                <div class="col-lg-3 col-md-6 col-6">
                    <h6 class="footer-title">Layanan Kampus</h6>
                    <ul class="footer-links">
                        <li><a href="https://igracias.telkomuniversity.ac.id" target="_blank" rel="noopener"><i class="fa-solid fa-arrow-up-right-from-square me-1 small"></i> i-Gracias Tel-U</a></li>
                        <li><a href="https://celoe.telkomuniversity.ac.id" target="_blank" rel="noopener"><i class="fa-solid fa-arrow-up-right-from-square me-1 small"></i> CeLOE LMS</a></li>
                        <li><a href="https://sit-v2.telkomuniversity.ac.id" target="_blank" rel="noopener"><i class="fa-solid fa-arrow-up-right-from-square me-1 small"></i> SIT Tugas Akhir</a></li>
                        <li><a href="https://fif.telkomuniversity.ac.id" target="_blank" rel="noopener"><i class="fa-solid fa-arrow-up-right-from-square me-1 small"></i> Fakultas Informatika</a></li>
                    </ul>
                </div>

                <!-- Kontak & Alamat -->
                <div class="col-lg-3 col-md-6">
                    <h6 class="footer-title">Kontak & Alamat</h6>
                    <p class="small text-muted mb-2">
                        <i class="fa-solid fa-location-dot text-danger me-2"></i>
                        Gedung Panambulai, Kawasan Telkom University Landmark Tower (TULT), Jl. Telekomunikasi No. 1, Terusan Buahbatu, Bandung 40257.
                    </p>
                    <p class="small text-muted mb-0">
                        <i class="fa-solid fa-envelope text-danger me-2"></i>
                        bif@telkomuniversity.ac.id
                    </p>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <p class="mb-0">
                    &copy; {{ date('Y') }} Program Studi S1 Informatika - Universitas Telkom. Seluruh hak cipta dilindungi.
                </p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5.3 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

    <!-- Custom Frontend Utilities -->
    <script>
        // Copy to clipboard helper
        function copyToClipboard(text, btnElement) {
            if (!navigator.clipboard) {
                const textarea = document.createElement('textarea');
                textarea.value = text;
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
                showCopiedTooltip(btnElement);
                return;
            }
            navigator.clipboard.writeText(text).then(() => {
                showCopiedTooltip(btnElement);
            });
        }

        function showCopiedTooltip(btn) {
            if (!btn) return;
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-check text-success"></i> Disalin!';
            btn.classList.add('btn-copied');
            setTimeout(() => {
                btn.innerHTML = originalHtml;
                btn.classList.remove('btn-copied');
            }, 2000);
        }
    </script>
    
    @yield('extra-js')
</body>

</html>
