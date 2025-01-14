@extends('layouts.app')

@section('content')
    <section class="wrapper">
        <header class="header">
            <h2 class="title">Schedule Management</h2>
        </header>

        <div class="content-box">
            <div class="table-container">
                <header class="table-header">
                    <h2 class="title" id="departmentInfo">
                        {{ isset($department) ? $department->department_name : ''}}
                        ({{ isset($department) ? $department->abbreviation : ''}})
                        <input type="hidden" name="department_id" id="department_id" value="{{ isset($department) ? $department->department_id : ''}}">
                    </h2>
                    <div class="table-header-actions">
                        <a href="{{ route('superadmin.schedules') }}"><i class="material-icons icon">arrow_left</i>Back</a>
                    </div>
                </header> 
                <div class="table-actions">
                    <div class="actions">
                        <div class="filters">
                            <select name="course_id" id="courseFilter">
                                <option value="" >All Course</option>
                                @foreach ($courses as $course)
                                    <option value="{{ $course->course_id }}">{{ $course->abbreviation }}</option>
                                @endforeach
                            </select>
                            <select name="year_level_id" id="yearLevelFilter">
                                <option value="" >All Year Level</option>
                                @foreach ($yearLevels as $yearLevel)
                                    <option value="{{ $yearLevel->year_level_id }}">{{ $yearLevel->year_level }}</option>
                                @endforeach
                            </select>
                            <select name="semester" id="semesterFilter">
                                <option value="" >All Semester</option>
                                @foreach ($semesters as $semester)
                                    <option value="{{ $semester }}">{{ $semester }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="search_wrap search_wrap_1">
                            <div class="search_box">
                                <input type="text" class="input" id="search" name="search_user" placeholder="Search....">
                                <div class="btn btn_common">
                                    <i class="search-icon material-icons">search</i>
                                </div>
                            </div>
                        </div>
                        <div class="buttons">
                            <button type="button" title="ADD" id="addSubject"><i class="material-icons icon">add</i></button>
                            <button type="button" title="BULK DELETE" id="bulkDeleteSubject"><i class="material-icons icon">delete</i></button>
                        </div>
                    </div>
                </div>
                <div class="table-info">
                    <p class="filterInfo"></p>
                    <button class="print" id="printButton"><i class="material-icons">print</i></button>
                </div>
                <div class="table-wrapper">
                    <table class="table">
                        <thead>
                            <tr class="heading">
                                <th><input type="checkbox" name="selectAllCheckbox" id="selectAllCheckbox" class="selectionBox"></th>
                                <th>Time | Day</th>
                                <th>Course No</th>
                                <th>Descriptive Title</th>
                                <th>Room</th>
                                <th>Year level | Section</th>
                                <th>Teacher</th>
                                <th class="pagination">
                                    <span class="previous" title="Previous"><i class="material-icons">keyboard_arrow_left</i></span>
                                    <span class="next" title="Next"><i class="material-icons">keyboard_arrow_right</i></span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="tableData">
                            
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
    const TEACHERS = @json($teachers);
    const COURSES = @json($courses);
    const YEARLEVELS = @json($yearLevels);
    const SEMESTERS = @json($semesters);
    const SECTIONS = @json($sections);
    const WEEKDAYS = @json($weekdays);
    const ROOMS = @json($rooms);
</script>
<script src="{{ asset('assets/js/schedules/subject_schedules.js') }}"></script>
@endsection