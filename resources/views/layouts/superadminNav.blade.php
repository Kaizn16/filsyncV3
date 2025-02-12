<div class="navigation">

    <nav class="nav">
        <a href="{{ route('superadmin.dashboard') }}">
            <img class="logo" src="{{ asset('assets/Images/FILSYNC-Logo-White.png') }}" alt="Logo">
        </a>
        <span class="toggle-sidebar"><i class="material-icons icon">menu</i></span>
    </nav>

    <sidebar class="sidebar">
        <header class="header">
            <img class="logo" src="{{ asset('assets/Images/FCU-Logo.png') }}" alt="FCU Logo">
            <div class="dropdown">
                <strong>SUPERADMIN</strong>
                <span class="toggle-dropdown"><i class="material-icons icon">arrow_drop_down</i></span>
            </div>
        </header>
        <uL class="sidebar-menu">
            <li class="item-menu">
                <a href="{{ route('superadmin.dashboard') }}" class="link {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}" title="Dashboard">
                    <i class="material-icons icon">dashboard</i>
                    <span class="link-name">Dahsboard</span>
                </a>
            </li>

            <li class="item-menu">
                <a href="{{ route('superadmin.users') }}" class="link {{ request()->routeIs('superadmin.users') ? 'active' : '' }}" title="Users">
                    <i class="material-icons icon">people</i>
                    <span class="link-name">Users</span>
                </a>
            </li>

            <li class="item-menu">
                <a href="{{ route('superadmin.academics') }}" class="link {{ request()->routeIs('superadmin.academics') || request()->routeIs('superadmin.academics.subjects') ? 'active' : '' }}" title="Academics">
                    <i class="material-icons icon">local_library</i>
                    <span class="link-name">Academics</span>
                </a>
            </li>

            <li class="item-menu">
                <a href="{{ route('superadmin.rooms') }}" class="link {{ request()->routeIs('superadmin.rooms')  || request()->routeIs('superadmin.schedules.subjects') ? 'active' : '' }}" title="Rooms">
                    <i class="material-icons icon">room_preferences</i>
                    <span class="link-name">Rooms</span>
                </a>
            </li>

            <li class="item-menu">
                <a href="{{ route('superadmin.settings') }}" class="link {{ request()->routeIs('superadmin.settings') ? 'active' : '' }}" title="Settings">
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