<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Parsba | @yield('title')</title>
   <link rel="stylesheet" href="{{ asset('css/app.css') }}">
<script src="{{ asset('js/app.js') }}"></script>
  @include('layouts.dashboard.style')
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.min.css" rel="stylesheet">

  @livewireStyles
  @stack('styles')
</head>

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    @include('layouts.dashboard.sidebar')
    <!--  Main wrapper -->
    <div class="body-wrapper">
      @include('layouts.dashboard.navbar')
      <div class="container-fluid">
        @yield('content')
        @include('layouts.dashboard.footer')
      </div>
    </div>
  </div>
  @include('layouts.dashboard.script')

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  @livewireScripts
  @stack('scripts')
</body>

</html>