<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', config('regency.company.name'))</title>
    <meta name="description" content="@yield('meta_description', config('regency.company.tagline'))">
    <link rel="canonical" href="{{ url()->current() }}">

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

    <meta property="og:site_name" content="{{ config('regency.company.name') }}">
    <meta property="og:title" content="@yield('title', config('regency.company.name'))">
    <meta property="og:description" content="@yield('meta_description', config('regency.company.tagline'))">
    <meta property="og:image" content="@yield('og_image', asset('images/og-default.jpg'))">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta name="twitter:card" content="summary_large_image">

    @stack('schema')

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=noto-sans-sinhala:400,600,700" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>document.documentElement.classList.add('js')</script>
</head>
<body class="min-h-screen bg-white text-stone-800 antialiased">

    <a href="#main" class="sr-only focus:not-sr-only focus:absolute focus:z-50 focus:bg-emerald-800 focus:px-4 focus:py-2 focus:text-white">
        Skip to content
    </a>

    @include('partials.header')

    <main id="main">
        @yield('content')
    </main>

    @include('partials.footer')
</body>
</html>
