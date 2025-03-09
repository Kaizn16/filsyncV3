@extends('layouts.student_layout')

@section('content')
    <section class="wrapper">
        <div class="table-container">
            <header class="table-header">
                <h2 class="title" id="departmentInfo">{{ $department->department_name }} - ({{ $department->abbreviation }})</h2>
            </header> 
            <div class="table-actions">
                <div class="actions">
                    <div class="filters">
                        <select name="semester" id="semester_filter">
                            <option value="">Select Semesters</option>
                            @foreach ($semesters as $semester)
                            <option value="{{ $semester }}" {{ isset($academicSettings) && $academicSettings->semester == $semester ? 'selected' : '' }}>
                                {{ $semester }}
                            </option>                                
                            @endforeach
                        </select>
                        <select name="course" id="course_filter">
                            <option value="">Select Course</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->course_id }}">{{ $course->abbreviation }}</option>
                            @endforeach
                        </select>
                        <select name="year_level" id="year_level_filter">
                            <option value="" >Select Year Level</option>
                            @foreach ($yearLevels as $yearLevel)
                                <option value="{{ $yearLevel }}">{{ $yearLevel }}</option>
                            @endforeach
                        </select>
                        <select name="section" id="section_filter">
                            <option value="" >Select Section</option>
                            @foreach ($sections as $section)
                                <option value="{{ $section->section_id }}">{{ $section->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <a href="{{ route('students') }}"><i class="material-icons">arrow_back</i>BACK</a>
                </div>
            </div>
            <div class="table-wrapper schedules">
                <div class="schedule-info">
                    <h1 class="title">Schedule of Classes</h1>
                    <div class="schedule-header">
                        <img src="{{ asset('assets/Images/FCU-Logo.png') }}" alt="FCU Inc. Logo">
                        <div class="filter-information">
                            <strong id="academic-year-text" data-academic_year="{{ $academicSettings->academic_year }}">A.Y {{ $academicSettings->academic_year }}</strong>
                            <strong id="semester-text">SEMESTER</strong>
                        </div>
                        <img src=" {{ asset('assets/Images/Departments Logo/' . $department->abbreviation . '.png') }}" alt="{{ $department->abbreviation }} Logo">
                    </div>
                </div>
                <header class="header">
                    <strong id="classIdentifierInfo">COURSE YEAR LEVEL-SECTION</strong>
                </header>
                <table class="table">
                    <thead>
                        <tr class="heading">
                            <th>CID</th>
                            <th>Course No</th>
                            <th>Descriptive Title</th>
                            <th>Credits</th>
                            <th colspan="3">Lecture</th>
                            <th>Teacher</th>
                        </tr>
                    </thead>
                    <tbody class="tableData">
                    <!-- POPULATE DATA -->
                        <tr class="extra-header">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>DAY</td>
                            <td>TIME</td>
                            <td>ROOM</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </section>  
@endsection
@section('script')
<script src="{{ asset('assets/js/schedules/student.schedules.js') }}"></script>
@endsection