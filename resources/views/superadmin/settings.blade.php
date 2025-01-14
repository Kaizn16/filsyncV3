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
<script>
    const changeThemeRoute = route('superadmin.changeTheme');
    let debounceTimeout;

    function debounce(func, delay) {
        return function (...args) {
            clearTimeout(debounceTimeout);
            debounceTimeout = setTimeout(() => func.apply(this, args), delay);
        };
    }

    function changeTheme(theme) {
        fetch(changeThemeRoute, {
            method: 'POST', 
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({
                theme: theme,
                _method: 'PUT',
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (theme === 'light') {
                    body.classList.remove("dark");
                    document.querySelector('.setting-options .option .toggle-theme .icon.active')?.classList.remove('active');
                    document.querySelector('.light-mode').classList.add('active');
                } else {
                    body.classList.add("dark");
                    document.querySelector('.setting-options .option .toggle-theme .icon.active')?.classList.remove('active');
                    document.querySelector('.dark-mode').classList.add('active');
                }
                showToast("info", `Theme has been set to ${theme} mode.`);
            }
        })
        .catch((error) => {
            console.error('Error:', error);
        });
    }

    document.querySelector('.light-mode').addEventListener('click', debounce(function() {
        changeTheme('light');
    }, 600));

    document.querySelector('.dark-mode').addEventListener('click', debounce(function() {
        changeTheme('dark');
    }, 600));
</script>
@endsection