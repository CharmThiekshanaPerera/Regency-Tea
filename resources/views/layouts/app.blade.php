<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      dir="{{ (config('regency.locales.'.app()->getLocale().'.rtl') ?? false) ? 'rtl' : 'ltr' }}"
      class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', config('regency.company.name'))</title>
    <meta name="description" content="@yield('meta_description', config('regency.company.tagline'))">
    <link rel="canonical" href="{{ url()->current() }}">

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

    @if (config('regency.google_site_verification'))
        <meta name="google-site-verification" content="{{ config('regency.google_site_verification') }}">
    @endif

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

    @if (config('regency.gtm_id'))
        {{-- Google Tag Manager --}}
        <script>
            (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start': new Date().getTime(),event:'gtm.js'});
            var f=d.getElementsByTagName(s)[0], j=d.createElement(s), dl=l!='dataLayer'?'&l='+l:'';
            j.async=true; j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl; f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','{{ config('regency.gtm_id') }}');
        </script>
        {{-- End Google Tag Manager --}}
    @endif
</head>
<body class="min-h-screen bg-white text-stone-800 antialiased">

    @if (config('regency.gtm_id'))
        {{-- Google Tag Manager (noscript) --}}
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ config('regency.gtm_id') }}"
                height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        {{-- End Google Tag Manager (noscript) --}}
    @endif

    <a href="#main" class="sr-only focus:not-sr-only focus:absolute focus:z-50 focus:bg-emerald-800 focus:px-4 focus:py-2 focus:text-white">
        Skip to content
    </a>

    @include('partials.topbar')
    @include('partials.header')

    <main id="main">
        @yield('content')
    </main>

    @include('partials.footer')
</body>
</html>
