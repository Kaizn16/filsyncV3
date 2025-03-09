@extends('layouts.app')

@section('content')
    <section class="wrapper">
        <header class="header">
            <h2 class="title">Welcome, {{ strtoupper(session('full_name')) }}!</h2>
        </header>

        <div class="content-box">
            <div class="boxes">
                <div class="box">
                    <header class="title">
                        <h1>Faculties</h1>
                    </header>
                    <div class="box-content">
                        <ul class="faculties-list">
                            <li class="item">
                                <strong class="faculty-name">Mark Romel F. Feguro</strong>
                            </li>
                            <li class="item">
                                <strong class="faculty-name">Joven Joshua Alovera</strong>
                            </li>
                            <li class="item">
                                <strong class="faculty-name">Villy Joe Delsocora</strong>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="box">
                    <header class="title">
                        <h1>Calendar</h1>
                    </header>
                    <div class="calendar-container">
                        <header class="header">
                            <h3 class="month-year">
                                <select id="monthSelect"></select>
                                <input type="number" id="yearInput" min="1900" max="2100">
                            </h3>
                            <nav>
                                <button id="prev"></button>
                                <button id="today">TODAY</button>
                                <button id="next"></button>
                            </nav>
                        </header>
                        <section class="calendar-contents">
                            <ul class="days">
                                <li>Sun</li>
                                <li>Mon</li>
                                <li>Tue</li>
                                <li>Wed</li>
                                <li>Thu</li>
                                <li>Fri</li>
                                <li>Sat</li>
                            </ul>
                            <ul class="dates"></ul>
                        </section>
                    </div>    
                </div>
            </div>
            <div class="table-container">
                <heder class="table-header">
                    <h2 class="title">
                        Teacher's Class
                    </h2>
                    <div class="table-header-actions">
                        <div class="search_wrap search_wrap_1">
                            <div class="search_box">
                                <input type="text" class="input" id="search" name="search_user" placeholder="Search....">
                                <div class="btn btn_common">
                                    <i class="search-icon material-icons">search</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </heder>
                <table class="table">
                    <thead>
                        <tr class="heading">
                            <th>TIME</th>
                            <th>TEACHERS</th>
                            <th>SUBJECT</th>
                            <th>ROOM</th>
                            <th>YEAR LEVEL</th>
                            <th>SECTION</th>
                        </tr>
                    </thead>
                    <tbody class="tableData">
                        
                    </tbody>
                </table>
            </div>
        </div>

    </section>
@endsection
@section('script')
@endsection