@extends('layouts.app')

@section('content')
    <section class="wrapper">
        <header class="header">
            <h2 class="title">Schedule Management </h2>
            <input type="hidden" id="schedule_draft_id" value="{{ $schedule_draft_id }}">
            <input type="hidden" id="course_id" value="{{ $course->course_id }}">
            <input type="hidden" id="academic_year" value="{{ $academic_year }}">
            <input type="hidden" id="semester" value="{{ $semester }}">
            <input type="hidden" id="year_level" value="{{ $year_level }}">
            <input type="hidden" id="section" value="{{ $section->section_id }}">
        </header>

        <div class="content-box">
            <div class="table-container">
                <header class="table-header">
                    <h2 class="title" id="departmentInfo">
                        Schedule Draft
                    </h2>
                    <div class="table-header-actions">
                        <a href="{{ route('admin.course.schedule', ['department' => $department->abbreviation, 'course' => $course->abbreviation]) }}"><i class="material-icons icon">arrow_left</i>Back</a>
                        <form action="{{ route('admin.schedule.draft.saved', ['department' => $department->abbreviation, 'course' => $course->abbreviation, 'schedule_draft_id' => $schedule_draft_id]) }}" method="post">
                            @method('PUT')
                            @csrf
                            <button type="submit"><i class="material-icons icon">check</i> Saved</button>
                        </form>
                        <form id="cancel-schedule-form" action="{{ route('admin.schedule.draft.cancel', ['department' => $department->abbreviation, 'course' => $course->abbreviation]) }}" method="post">
                            @method('DELETE')
                            @csrf
                            <input type="hidden" name="schedule_draft_id" value="{{ $schedule_draft_id ?? '' }}">
                        </form>
                        @if(isset($action) && $action !== 'edit')
                            <button id="cancel-button" type="button"><i class="material-icons icon">cancel</i> Cancel</button>
                        @endif
                        <a href="{{ route('admin.schedule.mydraft', ['department' => $department->abbreviation, 'course' => $course->abbreviation]) }}" title="MY DRAFTS">
                            <i class="material-icons icon">edit_calendar</i> 
                           My Drafts
                        </a>
                    </div>
                </header> 
                <div class="search_wrap search_wrap_1">
                    <div class="search_box">
                        <input type="text" class="input" id="search" name="search" placeholder="Search....">
                        <div class="btn btn_common">
                            <i class="search-icon material-icons">search</i>
                        </div>
                    </div>
                </div>
                <div class="subjects-list-container">
                    <ul class="subject-lists">
                       <!-- POPULATE DATA -->
                    </ul>
                </div>
                <div class="table-wrapper">
                    <header class="header">
                        <strong>Academic Year: {{ $academic_year }} - {{ $semester }}</strong>
                        <strong id="YearLevel-Section-Info">{{ $course->abbreviation }} {{ $year_level[0] }}-{{ $section->name }}</strong>
                    </header>
                    <table class="table">
                        <thead>
                            <tr class="heading">
                                <th>CID</th>
                                <th>Course No</th>
                                <th>Descriptive Title</th>
                                <th>Credit</th>
                                <th colspan="3">Lecture</th>
                                <th>Teacher</th>
                                <th></th>
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
    const TEACHERS = @json($teachers);
    const ROOMS = @json($rooms);
    const WEEKDAYS = @json($weekdays);
    const COURSE_NAME = "{{$course->abbreviation}}"; 
</script>
<script src="{{ asset('assets/js/schedules/schedule.draft.js') }}"></script>
@endsection