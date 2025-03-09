<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <title>{{ config('app.name', 'FILSYNC') }} - {{ Request::path() }}</title>

    <!----===== CSS ===== -->
    <link rel="stylesheet" href="{{ asset('assets/css/student.css') }}" />
    <link href="{{ asset('assets/css/material-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/icons/more-material-icons.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/toastr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}" />
    <!----===== CSS ===== -->
    @routes
</head>
<body class="loader-body">
    <main class="container" style="visibility: hidden;">
        <x-loading-spinner/>

        <div class="navigation">
            <nav class="nav">
                <a href="{{ route('students') }}">
                    <img class="logo" src="{{ asset('assets/Images/FILSYNC-Logo-White.png') }}" alt="Logo">
                </a>
            </nav>
        </div>

        <section class="content">
            @yield('content')
        </section>
        
    </main>
    
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/toastr.min.js') }}"></script>
    <script src="{{ asset('assets/js/toastnotif.js') }}"></script>
    <script src="{{ asset('assets/js/preloader.js') }}"></script>
    <script src="{{ asset('assets/js/lodash.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    @yield('script')
</body>
</html>