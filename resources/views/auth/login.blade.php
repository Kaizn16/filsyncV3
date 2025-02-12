<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">
        <link href="{{ asset('assets/css/material-icons.css') }}" rel="stylesheet">
        <title>FILSYNC</title>
    </head>

    <body>
        <div class="container" id="container">

            <div class="form-container sign-in">
                <form method="POST" action="{{ route('login.check') }}">
                    @csrf
                    <div class="header">
                        <img class="school-logo" src="{{ asset('assets/Images/FCU-Logo.png') }}" alt="Logo">
                        <img class="Main-Logo" src="{{ asset('assets/Images/FILSYNC-Logo-White.png') }}" alt="Logo">
                        <h1>LOGIN</h1>
                    </div>  
                    <input type="text" name="username" placeholder="Username" autocomplete="username" required>
                    @error('username')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="password-container">
                        <input type="password" id="signin-password" name="password" placeholder="Password">
                        <i class="material-icons toggle-password" id="togglePassword-signin">visibility</i>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    {{-- <div class="checkbox-form">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember">
                            {{ __('Remember Me') }}
                        </label>
                    </div> --}}
                    <button>LOGIN</button>
                </form>
            </div>

            <div class="logo-container">
                <img src="{{ asset('assets/Images/FCU-Logo.png') }}" alt="Logo">
            </div>
            
        </div>
        <script src="{{ asset('assets/js/login.js') }}"></script>
    </body>
</html>