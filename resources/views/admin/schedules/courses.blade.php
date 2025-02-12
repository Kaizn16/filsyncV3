@extends('layouts.app')

@section('content')
    <section class="wrapper">
        <header class="header">
            <h2 class="title">Schedule Management</h2>
        </header>

        <div class="content-box">
           <div class="list-container">
                <header class="header">
                    <h2 class="title">
                        {{ $department ?? '' }} - Courses
                    </h2>
                    <div class="table-header-actions">
                        <a href="{{ route('admin.schedules') }}"><i class="material-icons icon">arrow_left</i>Back</a>
                    </div>
                </header>
                <ul class="departments-list">
                    @foreach ($courses as $course)
                    <li class="item">
                        <a href="{{ route('admin.course.schedule', ['department' => $department, 'course' => $course->abbreviation]) }}" class="link">
                            <strong class="title">{{ $course->course_name }} ({{ $course->abbreviation }})</strong>
                            <i class="material-icons icon">chevron_right</i>
                        </a>
                    </li>
                    @endforeach
                </ul>
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
@endsection