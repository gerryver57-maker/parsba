<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Parsba | @yield('title')</title>
  @include('layouts.dashboardadmin.style')

  @livewireStyles
</head>

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    @include('layouts.dashboardadmin.sidebar')
    <!--  Main wrapper -->
    <div class="body-wrapper">
      @include('layouts.dashboardadmin.navbar')
      <div class="container-fluid">
        @yield('content')
        @include('layouts.dashboardadmin.footer')
      </div>
    </div>
  </div>
  @include('layouts.dashboardadmin.script')

  @livewireScripts

</body>

</html>