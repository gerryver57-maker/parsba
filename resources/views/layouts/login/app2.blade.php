<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Login | Sistem Informasi PARSBA</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Styles -->
    @include('layouts.login.style')
    @livewireStyles
</head>

<body>

    <!-- Login Component -->
        @yield('content')

    <!-- Scripts -->
    @include('layouts.login.script')
    @livewireScripts

</body>
</html>
