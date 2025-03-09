@extends('layouts.app')

@section('content')
    <section class="wrapper">
        <header class="header">
            <h2 class="title">Settings</h2>
        </header>

        <div class="content-box">

            <div class="setting-options">
                
                <div class="option">

                    <header class="header">
                        <strong>Theme</strong>
                    </header>
        
                    <div class="form-group">
                        <span class="toggle-theme" id="light-mode"><i class="material-icons light-mode icon {{ isset($setting) && $setting->is_theme_dark == 0 ? 'active' : '' }}" title="Light Mode">light_mode</i>Light Mode</span>
                        <span class="toggle-theme" id="dark-mode"><i class="material-icons dark-mode icon {{ isset($setting) && $setting->is_theme_dark == 1 ? 'active' : '' }}" title="Dark Mode">dark_mode</i>Dark Mode</span>
                    </div>
                </div>
            </div>
              
        </div>

    </section>
@endsection

@section('script')
<script src="{{ asset('assets/js/settings/settings.js') }}"></script>
@endsection