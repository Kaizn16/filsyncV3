@extends('layouts.app')

@section('content')
    <section class="wrapper">
        <header class="header">
            <h2 class="title">Course/Subject Management</h2>
        </header>

        <div class="content-box">
            <div class="table-container">
                <header class="table-header">
                    <h2 class="title">
                        <span data-department_name="{{ isset($department) ? $department->department_name : ''}}">{{ isset($department) ? $department->department_name : ''}}</span>
                        <span data-department_abbreviation="{{ isset($department) ? $department->abbreviation : ''}}">({{ isset($department) ? $department->abbreviation : ''}})</span>
                        <input type="hidden" name="department_id" id="department_id" value="{{ isset($department) ? $department->department_id : ''}}">
                    </h2>
                    <div class="table-header-actions">
                        <a href="{{ route('superadmin.academics') }}"><i class="material-icons icon">arrow_left</i>Back</a>
                    </div>
                </header> 
                <div class="table-actions">
                    <div class="actions">
                        <div class="search_wrap search_wrap_1">
                            <div class="search_box">
                                <input type="text" class="input" id="search" name="search_user" placeholder="Search....">
                                <div class="btn btn_common">
                                    <i class="search-icon material-icons">search</i>
                                </div>
                            </div>
                        </div>
                        <div class="filters">
                            <select name="course" id="courseFilter">
                                <option value="" >All Course</option>
                                @foreach ($courses as $course)
                                    <option value="{{ $course->course_id }}">{{$course->course_name}} ({{ $course->abbreviation }})</option>
                                @endforeach
                            </select>
                            <select name="year_level" id="yearLevelFilter">
                                <option value="" >All Year Level</option>
                                @foreach ($yearLevels as $yearLevel)
                                    <option value="{{ $yearLevel }}">{{ $yearLevel }}</option>
                                @endforeach
                            </select>
                            <select name="semester" id="semesterFilter">
                                <option value="" >All Semester</option>
                                @foreach ($semesters as $semester)
                                    <option value="{{ $semester }}">{{ $semester }}</option>
                                @endforeach
                            </select>
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
                    <div class="page-info">
                    
                    </div>
                    <table class="table">
                        <thead>
                            <tr class="heading">
                                <th><input type="checkbox" name="selectAllCheckbox" id="selectAllCheckbox" class="selectionBox"></th>
                                <th>Course</th>
                                <th>Course No</th>
                                <th>Descriptive Title</th>
                                <th>Credit Units</th>
                                <th>Year Level</th>
                                <th>Semester</th>
                                <th class="pagination">
                                    <select name="paginate" id="paginate">
                                        <option value="10" selected>10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                        <option value="1000">1000</option>
                                    </select>
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
<script>
    const base_route = "{{ asset('assets/css/main.css') }}";
    const BASE_ASSETS_PATH = `{{ asset('assets') }}`;
    const yearLevels = @json($yearLevels);
    const academic_years = @json($academic_years);
</script>
@section('script')
<script src="{{ asset('assets/js/academics/subjects.js') }}"></script>
@if (session('message'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        showToast("{{ session('type') }}", "{{ session('message') }}");
    });
</script>
@endif
@endsection