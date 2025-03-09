<div class="navigation">

    <nav class="nav">
        <span class="toggle-sidebar"><i class="material-icons icon">menu</i></span>
        <a href="{{ route('vpaa.dashboard') }}">
            <img class="logo" src="{{ asset('assets/Images/FILSYNC-Logo-White.png') }}" alt="Logo">
        </a>
    </nav>

    <sidebar class="sidebar">
        <header class="header">
            <img class="logo" src="{{ asset('assets/Images/FCU-Logo.png') }}" alt="FCU Logo">
            <div class="dropdown">
                <strong>VPAA</strong>
                <span class="toggle-dropdown"><i class="material-icons icon">arrow_drop_down</i></span>
            </div>
        </header>
        <uL class="sidebar-menu">
            <li class="item-menu">
                <a href="{{ route('vpaa.dashboard') }}" class="link {{ request()->routeIs('vpaa.dashboard') ? 'active' : '' }}" title="Dashboard">
                    <i class="material-icons icon">dashboard</i>
                    <span class="link-name">Dahsboard</span>
                </a>
            </li>

            <li class="item-menu">
                <a href="{{ route('vpaa.schedules') }}" 
                    class="link {{ request()->routeIs('vpaa.schedules') || 
                    request()->routeIs('vpaa.view.schedules')
                     ? 'active' : '' }}" 
                    title="Schedules">
                    <i class="material-icons icon">today</i>
                    <span class="link-name">Schedules</span>
                </a>
            </li>

            <li class="item-menu">
                <a href="{{ route('vpaa.settings') }}" class="link {{ request()->routeIs('vpaa.settings') ? 'active' : '' }}" title="Settings">
                    <i class="material-icons icon">settings</i>
                    <span class="link-name">Settings</span>
                </a>
            </li>

            <li class="item-menu">
                <a class="link" title="Logout" id="Signout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="material-icons icon">logout</i>
                    <span class="link-name">Logout</span>
                    <form id="logout-form" action="{{ route('auth.logout') }}" method="POST" style="display: none;">
                        @csrf
                        @method('POST')
                    </form>
                </a>
            </li>

        </uL>
    </sidebar>
</div>