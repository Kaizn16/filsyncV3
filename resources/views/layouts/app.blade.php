<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-role" content="{{ Auth::user() ? Auth::user()->role->role_type : '' }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <title>{{ config('app.name', 'FILSYNC') }} - {{ Request::path() }}</title>

    <!----===== CSS ===== -->
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}" />
    <link href="{{ asset('assets/css/material-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/icons/more-material-icons.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/toastr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}" />
    <!----===== CSS ===== -->
    @routes
</head>
<body {{ Auth::user()->settings->first()?->is_theme_dark == 1 ? 'class=dark' : '' }} class="loader-body">
    <main class="container" style="visibility: hidden;">
        <x-loading-spinner/>
        @if (Auth::user()->role->role_type == "superadmin")
            @include('layouts.superadminNav')
        @elseif (Auth::user()->role->role_type == "admin")
            @include('layouts.adminNav')
        @elseif (Auth::user()->role->role_type == "teacher")
            @include('layouts.teacherNav')
        @elseif (Auth::user()->role->role_type == "vpaa")
            @include('layouts.vpaaNav')
        @elseif (Auth::user()->role->role_type == "registrar")
            @include('layouts.registrarNav')
        @elseif (Auth::user()->role->role_type == "hr")
            @include('layouts.hrNav')
        @else
            @include('layouts.404')
        @endif
        
        <section class="content">
            @yield('content')
        </section>

    </main>

    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/toastr.min.js') }}"></script>
    <script src="{{ asset('assets/js/sidebar.js') }}"></script>
    <script src="{{ asset('assets/js/chart.js') }}"></script>
    <script src="{{ asset('assets/js/sweetalert2-11.js') }}"></script>
    <script src="{{ asset('assets/js/preloader.js') }}"></script>
    <script src="{{ asset('assets/js/toastnotif.js') }}"></script>
    <script src="{{ asset('assets/js/lodash.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    @yield('script')
</body>
</html>
