@extends('layouts.student_layout')

@section('content')
    <section class="wrapper">
        <header class="header">
            <img src="{{ asset('assets/Images/FCU-Logo.png') }}" alt="FCU-INC-LOGO">
            <h2 class="title">DEPARTMENTS</h2>
        </header>

        <div class="list-wrapper">
            <ul class="cards-container">
                @foreach ($departments as $department)
                    <li class="card">
                        <div class="department-info">
                            <img src="{{ asset('assets/Images/Departments Logo/' . $department->abbreviation . '.png') }}" class="logo" alt="Department Logo">
                            <strong class="department-name">{{ $department->department_name }}<span class="abbreviation">({{ $department->abbreviation }})</span></strong>
                        </div>
                        <a href="{{ route('student.schedules', ['department_abbreviation' => $department->abbreviation]) }}" class="link" title="View {{ $department->abbreviation }} Schedule">View Schedule</a>
                    </li>
                @endforeach
            </ul>
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