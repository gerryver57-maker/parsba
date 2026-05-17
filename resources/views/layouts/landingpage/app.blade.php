<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    {{-- TITLE --}}
    <title>
        PARSBA - Sistem Informasi Pertanian Padi Terintegrasi BMKG
    </title>

    {{-- SEO --}}
    <meta name="description"
          content="PARSBA adalah sistem informasi pertanian padi berbasis web dengan integrasi cuaca BMKG untuk membantu petani mengelola tanam, pemupukan, panen, dan monitoring lahan di Kabupaten Pasaman, Sumatera Barat.">

    <meta name="keywords"
          content="PARSBA, pertanian padi, BMKG, sistem pertanian, Pasaman, Sumatera Barat">

    <meta name="author"
          content="PARSBA">

    <meta name="robots"
          content="index, follow">

    <meta name="theme-color"
          content="#2E7D32">

    <meta name="google-site-verification"
          content="UdNqsSeXhOzi183M87W1mJhzTASDciFRQcVOcMUNvik">

    {{-- CANONICAL --}}
    <link rel="canonical"
          href="https://parsba.byethost24.com/">

    {{-- FAVICON --}}
    <link rel="icon"
          type="image/png"
          href="{{ asset('dashboard/images/logos/logo.png') }}">

    {{-- OPEN GRAPH --}}
    <meta property="og:type"
          content="website">

    <meta property="og:title"
          content="PARSBA - Sistem Informasi Pertanian Padi">

    <meta property="og:description"
          content="Platform digital pertanian padi terintegrasi BMKG untuk monitoring cuaca, pemupukan, dan panen.">

    <meta property="og:url"
          content="https://parsba.byethost24.com/">

    <meta property="og:image"
          content="https://parsba.byethost24.com/dashboard/images/logos/logo.webp">

    {{-- TWITTER --}}
    <meta name="twitter:card"
          content="summary_large_image">

    <meta name="twitter:title"
          content="PARSBA">

    <meta name="twitter:description"
          content="Sistem Informasi Pertanian Padi Terintegrasi API BMKG">

    <meta name="twitter:image"
          content="https://parsba.byethost24.com/dashboard/images/logos/logo.png">


    {{-- PRELOAD HERO IMAGE --}}
    <link rel="preload"
          as="image"
          href="{{ asset('dashboard/images/padi/background1.webp') }}">


    {{-- BOOTSTRAP --}}
    <link rel="preload"
      href="dashboard/libs/bootstrap/dist/css/bootstrap.min.css"
      as="style"
      onload="this.onload=null;this.rel='stylesheet'">

    <noscript>
    <link rel="stylesheet"
          href="dashboard/libs/bootstrap/dist/css/bootstrap.min.css">
    </noscript>

    {{-- BOOTSTRAP ICON --}}
	<link rel="preload"
      href="dashboard/libs/bootstrap/dist/font/bootstrap-icons.css"
      as="style"
      onload="this.onload=null;this.rel='stylesheet'">

    <noscript>
    <link rel="stylesheet"
          href="dashboard/libs/bootstrap/dist/font/bootstrap-icons.css">
    </noscript>
    {{-- AOS --}}
	<link rel="preload"
      href="dashboard/libs/aos-master/dist/aos.css"
      as="style"
      onload="this.onload=null;this.rel='stylesheet'">

    <noscript>
    <link rel="stylesheet"
          href="dashboard/libs/aos-master/dist/aos.css">
    </noscript>

    {{-- MAIN CSS --}}
    <link rel="preload"
          href="{{ asset('dashboard/css/landing.css') }}"
          as="style"
          onload="this.onload=null;this.rel='stylesheet'">

    <noscript>
        <link rel="stylesheet"
              href="{{ asset('dashboard/css/landing.css') }}">
    </noscript>
    @livewireStyles

</head>
<body>

    @yield('content')

    {{-- BOOTSTRAP --}}
    <script defer
            src="dashboard/libs/bootstrap/dist/js/bootstrap.bundle.min.js">
    </script>

    {{-- AOS --}}
    <script defer
            src="dashboard/libs/aos-master/dist/aos.js">
    </script>

    <script>
        window.addEventListener('load', () => {
            AOS.init({
                duration: 800,
                once: true
            });
        });
    </script>

    {{-- JSON LD --}}
    <script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'WebApplication',
    'name' => 'PARSBA',
    'url' => 'https://parsba.byethost24.com',
    'applicationCategory' => 'AgricultureApplication',
    'operatingSystem' => 'Web',
    'description' => 'Sistem Informasi Manajemen Pertanian Padi Terintegrasi API BMKG',
    'creator' => [
        '@type' => 'Organization',
        'name' => 'PARSBA'
    ],
    'areaServed' => [
        '@type' => 'AdministrativeArea',
        'name' => 'Nagari Bahagia Padang Gelugua, Kabupaten Pasaman, Sumatera Barat'
    ]
], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}
    </script>

    @livewireScripts

</body>
</html>