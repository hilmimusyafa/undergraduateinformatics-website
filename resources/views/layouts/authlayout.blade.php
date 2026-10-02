<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Form') - Dashboard Informasi S1 Informatika</title>
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link href="/css/fonts/figtree.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/styleAuth.css">
    <link href="/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/vendor/fontawesome/css/all-5.15.4.min.css">
    <script src="/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="/vendor/fontawesome/css/all.min.css">
</head>

<body class="admin-auth">
    <main>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    @include('partials.Alerts')
                    @yield('content')
                </div>
            </div>
        </div>
    </main>
</body>

</html>
