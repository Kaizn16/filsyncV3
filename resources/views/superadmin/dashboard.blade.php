@extends('layouts.app')

@section('content')
    <section class="wrapper">
        <header class="header">
            <h2 class="title">Welcome, {{ strtoupper(session('role', 'Guest')) }}!</h2>
        </header>

        <div class="content-box">

            <ul class="statwidgets">
                <li class="statwidget-item">
                    <i class="material-icons icon">people</i>
                    <div class="stat-info">
                        <strong class="title">{{ $totalUsers ?? 0 }}</strong>
                        <label class="subtitle">Users</label>
                    </div>
                </li>

                <li class="statwidget-item">
                    <i class="material-icons icon">school</i>
                    <div class="stat-info">
                        <strong class="title">{{ $totalDepartments ?? 0}}</strong>
                        <label class="subtitle">Departments</label>
                    </div>
                </li>

                <li class="statwidget-item">
                    <i class="material-icons icon">library_books</i>
                    <div class="stat-info">
                        <strong class="title">{{ $totalCourses ?? 0 }}</strong>
                        <label class="subtitle">Courses</label>
                    </div>
                </li>


                <li class="statwidget-item">
                    <i class="material-icons icon">location_on</i>
                    <div class="stat-info">
                        <strong class="title">0</strong>
                        <label class="subtitle">Resources</label>
                    </div>
                </li>


                <li class="statwidget-item">
                    <i class="material-icons icon">watch_later</i>
                    <div class="stat-info">
                        <strong class="title">{{ $totalSchedules ?? 0 }}</strong>
                        <label class="subtitle">Schedules</label>
                    </div>
                </li>

            </ul>

        </div>

    </section>
@endsection

@section('script')
@endsection