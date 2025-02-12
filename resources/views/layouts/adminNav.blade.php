<div class="navigation">

    <nav class="nav">
        <span class="toggle-sidebar"><i class="material-icons icon">menu</i></span>
        <a href="{{ route('admin.dashboard') }}">
            <img class="logo" src="{{ asset('assets/Images/FILSYNC-Logo-White.png') }}" alt="Logo">
        </a>
    </nav>

    <sidebar class="sidebar">
        <header class="header">
            <img class="logo" src="{{ asset('assets/Images/FCU-Logo.png') }}" alt="FCU Logo">
            <div class="dropdown">
                <strong>ADMIN</strong>
                <span class="toggle-dropdown"><i class="material-icons icon">arrow_drop_down</i></span>
            </div>
        </header>
        <uL class="sidebar-menu">
            <li class="item-menu">
                <a href="{{ route('admin.dashboard') }}" class="link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" title="Dashboard">
                    <i class="material-icons icon">dashboard</i>
                    <span class="link-name">Dahsboard</span>
                </a>
            </li>

            <li class="item-menu">
                <a href="{{ route('admin.users') }}" class="link {{ request()->routeIs('admin.users') ? 'active' : '' }}" title="Users">
                    <i class="material-icons icon">people</i>
                    <span class="link-name">Users</span>
                </a>
            </li>

            <li class="item-menu">
                <a href="{{ route('admin.schedules') }}" 
                    class="link {{ request()->routeIs('admin.schedules') || 
                    request()->routeIs('admin.department.courses') || 
                    request()->routeIs('admin.course.schedule') ||
                    request()->routeIs('admin.schedule.draft.create') ||
                    request()->routeIs('admin.schedule.mydraft')
                     ? 'active' : '' }}" 
                    title="Schedules">
                    <i class="material-icons icon">today</i>
                    <span class="link-name">Schedules</span>
                </a>
            </li>

            <li class="item-menu">
                <a href="{{ route('admin.rooms') }}" class="link {{ request()->routeIs('admin.rooms')  || request()->routeIs('admin.schedules.subjects') ? 'active' : '' }}" title="Rooms">
                    <i class="material-icons icon">room_preferences</i>
                    <span class="link-name">Rooms</span>
                </a>
            </li>

            <li class="item-menu">
                <a href="" class="link" title="Reports">
                    <i class="material-icons icon">insert_chart</i>
                    <span class="link-name">Reports</span>
                </a>
            </li>

            <li class="item-menu">
                <a href="{{ route('admin.settings') }}" class="link {{ request()->routeIs('admin.settings') ? 'active' : '' }}" title="Settings">
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