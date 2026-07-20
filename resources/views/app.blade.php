<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Portal Informasi - Program Studi Sarjana Informatika' }}</title>
    <meta name="description" content="{{ $description ?? 'Sumber informasi resmi Program Studi Sarjana Informatika Telkom University yang menyediakan berbagai informasi perkuliahan.' }}">
    <meta property="og:title" content="{{ $ogTitle ?? $title ?? 'Portal Informasi - Program Studi Sarjana Informatika' }}">
    <meta property="og:description" content="{{ $ogDescription ?? $description ?? 'Sumber informasi resmi Program Studi Sarjana Informatika Telkom University yang menyediakan berbagai informasi perkuliahan.' }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $siteName ?? 'Telkom University' }}">
    <meta property="og:image" content="{{ $ogImage ?? '/images/DummyImage.png' }}">
    <meta property="og:url" content="{{ $ogUrl ?? url('/') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    @if(isset($jsonLd) && $jsonLd)
    <script type="application/ld+json">
        {!! json_encode($jsonLd) !!}
    </script>
    @endif
    <script>
        window.__INITIAL_DATA__ = {!! json_encode($initialData ?? null) !!};
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.tsx'])
</head>
<body>
    <div id="root"></div>
</body>
</html>
