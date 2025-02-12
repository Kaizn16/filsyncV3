@extends('layouts.app')

@section('content')
    <section class="wrapper">
        <header class="header">
            <h2 class="title">Rooms Management</h2>
        </header>

        <div class="content-box">
            <div class="table-container">
                <header class="table-header">
                    <h2 class="title" id="departmentInfo">
                    </h2>
                    <div class="table-header-actions">
                       
                    </div>
                </header> 
                <div class="table-actions">
                    <div class="actions">
                        <div class="filters">
                            <select name="academic_year_filter" id="academic_year_filter">
                                <option value="" >Academic Year</option>
                                @foreach ($academic_years as $academic_year)
                                <option value="{{ $academic_year }}" {{ isset($academicSetting) && $academicSetting->academic_year == $academic_year ? 'selected' : '' }}>
                                    {{ $academic_year }}
                                </option>                                
                                @endforeach
                            </select>
                            <select name="semester" id="semester_filter">
                                <option value="" >Semesters</option>
                                @foreach ($semesters as $semester)
                                <option value="{{ $semester }}" {{ isset($academicSetting) && $academicSetting->semester == $semester ? 'selected' : '' }}>
                                    {{ $semester }}
                                </option>                                
                                @endforeach
                            </select>
                            <select name="weekdays" id="weekdays_filter">
                                @foreach ($scheduleWeekdays as $schedule)
                                    @php
                                        $daysString = implode(', ', $schedule['days']);
                                        $shortDaysString = implode(', ', array_map(fn($day) => substr($day, 0, 3), $schedule['days']));
                                    @endphp
                                    <option value="{{ $daysString }}">{{ $shortDaysString }}</option>
                                @endforeach
                            </select>                                                                                                                  
                        </div>
                    </div>
                </div>
                <div class="table-info">
                    <p class="filterInfo"></p>
                    <button class="print" id="printButton" title="PRINT"><i class="material-icons">print</i></button>
                </div>
                <div class="table-wrapper schedules">
                    <div class="schedule-info">
                        <h1 class="title">Room Unitilization Schedule</h1>
                        <div class="schedule-header">
                            <img src="{{ asset('assets/Images/FCU-Logo.png') }}" alt="FCU Inc. Logo">
                            <div class="filter-information">
                                <strong id="academic-year-text">A.Y ACADEMIC YEAR</strong>
                                <strong id="semester-text">SEMESTER</strong>
                            </div>
                            <img src=" {{ asset('assets/Images/Departments Logo/' . $department . '.png') }}" alt="{{ $department }} Logo">
                        </div>
                    </div>
                    <table class="table room-utilization">
                        <thead>
                            <tr class="heading">
                                <!-- POPULATE HEADER -->
                            </tr>
                        </thead>
                        <tbody class="tableData">
                            <!-- POPULATE DATA -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </section>
@endsection
@section('script')
@if (session('message'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        showToast("{{ session('type') }}", "{{ session('message') }}");
    });
</script>
@endif
<script>
    const base_route = "{{ asset('assets/css/main.css') }}";
    const BASE_ASSETS_PATH = `{{ asset('assets') }}`;
    const MATERIAL_ICONS = `{{ asset('assets/css/material-icons.css') }}`;
    const ROOMS = @json($rooms);
    const WEEKDAYS = @json($weekdays);
</script>
<script src="{{ asset('assets/js/rooms/admin.rooms.js') }}"></script>
@endsection