@extends('layouts.app')

@section('content')
    <section class="wrapper">
        <header class="header">
            <h2 class="title">Schedule Management</h2>
            <input type="hidden" name="course" id="course" value="{{ $course }}"/>
        </header>

        <div class="content-box">
            <div class="table-container">
                <header class="table-header">
                    <h2 class="title" id="departmentInfo">
                    </h2>
                    <div class="table-header-actions">
                        <a href="{{ route('admin.department.courses', ['department' => $department]) }}"><i class="material-icons icon">arrow_left</i>Back</a>
                        <div class="buttons" style="{{ $teacher->department->abbreviation === $department ? '' : 'display: none;' }}">
                            @if(isset($scheduleDraft))
                                <form action="{{ route('admin.schedule.draft.create', ['department' => $department, 'course' => $course]) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" title="CONTINUE" class="btn-link">
                                        <i class="material-icons icon">next_plan</i>
                                        Continue Drafting Schedule
                                    </button>
                                </form>
                            @else
                                <a href="javascript:void(0);" id="addSubject" title="ADD">
                                    <i class="material-icons icon">add</i>
                                    Create Schedule Draft
                                </a>
                            @endif
                            <a href="{{ route('admin.schedule.mydraft', ['department' => $department, 'course' => $course]) }}" title="MY DRAFTS">
                                <i class="material-icons icon">edit_calendar</i> 
                               My Drafts
                            </a>
                        </div>
                    </div>
                </header> 
                <div class="table-actions">
                    <div class="actions">
                        <div class="filters">
                            <div class="form-group">
                                <label for="academic_year_filter">Academic Year</label>
                                <input type="text" id="academic_year_filter" name="academic_year_filter" value="{{ $academicSetting->academic_year }}">
                            </div>
                            <select name="semester" id="semester_filter">
                                <option value="" >Semesters</option>
                                @foreach ($semesters as $semester)
                                <option value="{{ $semester }}" {{ isset($academicSetting) && $academicSetting->semester == $semester ? 'selected' : '' }}>
                                    {{ $semester }}
                                </option>                                
                                @endforeach
                            </select>
                            <select name="year_level" id="year_level_filter">
                                <option value="" >Year Levels</option>
                                @foreach ($yearLevels as $yearLevel)
                                <option value="{{ $yearLevel }}">{{ $yearLevel }}</option>
                            @endforeach
                            </select>
                            <select name="section" id="section_filter">
                                <option value="" >Sections</option>
                                @foreach ($sections as $section)
                                    <option value="{{ $section->section_id }}">Section {{ $section->name }}</option>
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
                        <h1 class="title">Schedule of Classes</h1>
                        <div class="schedule-header">
                            <img src="{{ asset('assets/Images/FCU-Logo.png') }}" alt="FCU Inc. Logo">
                            <div class="filter-information">
                                <strong id="academic-year-text">A.Y ACADEMIC YEAR</strong>
                                <strong id="semester-text">SEMESTER</strong>
                            </div>
                            <img src=" {{ asset('assets/Images/Departments Logo/' . $department . '.png') }}" alt="{{ $department }} Logo">
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
</script>
<script>
    document.getElementById('addSubject').addEventListener('click', function () {
        Swal.fire({
            html: `
                <section class="form-wrapper">
                    <div class="form-container">
                        <header class="form-header">
                            <h1 class="title">Create Schedule Draft</h1>
                            <span class="close"><i class="material-icons icon">close</i></span>
                        </header>
                        <form id="scheduleDraftForm" class="form-content" method="POST" action="{{ route('admin.schedule.draft.create', ['department' => $department, 'course' => $course]) }}">
                            @csrf
                            <div class="form-group">
                                <label for="academic_year">Academic Year</label>
                                <input type="text" id="academic_year" name="academic_year" placeholder="Enter School Year" value="{{ $academicSetting->academic_year }}">
                            </div>

                            <div class="form-group">
                                <label for="semesterFilter">Semester</label>
                                <select id="semesterFilter" name="semester">
                                    <option value="">Semester</option>
                                    @foreach ($semesters as $semester)
                                    <option value="{{ $semester }}" {{ isset($academicSetting) && $academicSetting->semester == $semester ? 'selected' : '' }}>
                                        {{ $semester }}
                                    </option>                                
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="yearLevelFilter">Year Level</label>
                                <select id="yearLevelFilter" name="year_level">
                                    <option value="">Year Level</option>
                                    @foreach ($yearLevels as $yearLevel)
                                        <option value="{{ $yearLevel }}">{{ $yearLevel }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="sectionFilter">Section</label>
                                <select id="sectionFilter" name="section">
                                    <option value="">Section</option>
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->section_id }}">Section {{ $section->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </div>
                </section>
            `,
            confirmButtonText: 'ADD',
            confirmButtonColor: '#5296BE',
            showCancelButton: true,
            cancelButtonColor: "#d33",
            cancelButtonText: 'CANCEL',
            reverseButtons: true,
            customClass: {
                popup: 'custom-swal-popup',
            },
            preConfirm: () => {
                const academicYear = document.getElementById('academic_year').value;
                const semester = document.getElementById('semesterFilter');
                const yearLevel = document.getElementById('yearLevelFilter');
                const section = document.getElementById('sectionFilter');

                if (!academicYear || !semester || !yearLevel || !section) {
                    Swal.showValidationMessage('Please fill out all fields.');
                    return false;
                }

                const form = document.getElementById('scheduleDraftForm');
                form.submit();
            }
        });
        document.querySelector('.custom-swal-popup .close').addEventListener('click', () => {
        Swal.close();
    }); 
    });
</script>
<script src="{{ asset('assets/js/schedules/schedules.main.js') }}"></script>
@endsection


