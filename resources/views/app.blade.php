<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title data-ssr="true">{{ $title ?? 'Portal Informasi Sarjana Informatika' }}</title>
    <meta data-ssr="true" name="description" content="{{ $description ?? 'Sumber informasi resmi Program Studi Sarjana Informatika Telkom University yang menyediakan berbagai informasi perkuliahan.' }}">
    <meta data-ssr="true" property="og:title" content="{{ $ogTitle ?? 'Portal Informasi Sarjana Informatika' }}">
    <meta data-ssr="true" property="og:description" content="{{ $ogDescription ?? 'Sumber informasi resmi Program Studi Sarjana Informatika Telkom University yang menyediakan berbagai informasi perkuliahan.' }}">
    <meta data-ssr="true" property="og:type" content="website">
    <meta data-ssr="true" property="og:site_name" content="{{ $siteName ?? 'Telkom University' }}">
    <meta data-ssr="true" property="og:image" content="{{ $ogImage ?? '/public/images/banner.png' }}">
    <meta data-ssr="true" property="og:url" content="{{ $ogUrl ?? url('/') }}">
    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="Portal IF" />
    <link rel="manifest" href="/site.webmanifest" />
    @if(isset($jsonLd) && $jsonLd)
    <script type="application/ld+json">
        {!! json_encode($jsonLd) !!}
    </script>
    @endif
    <script>
        window.__INITIAL_DATA__ = {!! json_encode($initialData ?? null) !!};
    </script>
    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.tsx'])
</head>
<body>
    <div id="root"></div>
</body>
</html>
