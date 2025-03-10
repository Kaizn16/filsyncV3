@extends('layouts.app')

@section('content')
    <section class="wrapper">
        <header class="header">
            <h2 class="title">Schedules</h2>
        </header>

        <div class="content-box">
            <div class="table-container">
                <heder class="table-header">
                    <h2 class="title">
                        Schedule Drafts
                    </h2>
                </heder>
                <div class="table-header-actions">
                    <div class="search_wrap search_wrap_1">
                        <div class="search_box">
                            <input type="text" class="input" id="search" name="search" placeholder="Search....">
                            <div class="btn btn_common">
                                <i class="search-icon material-icons">search</i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-actions">
                    <div class="actions">
                        <div class="filters">
                            <select name="academic_year" id="academic_year_filter">
                                <option value="">Academic Year</option>
                                @foreach ($academicYears as $academicYear)
                                    <option>{{ $academicYear->academic_year }}</option>
                                @endforeach
                            </select>
                            <select name="semester" id="semester_filter">
                                <option value="">Semester</option>
                                @foreach ($semesters as $semester)
                                    <option value="{{ $semester }}">{{ $semester }}</option>
                                @endforeach
                            </select>
                            <select name="status" id="status_filter">
                                <option value="">All Status</option>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                    </div>
                </div>
                <table class="table">
                    <thead>
                        <tr class="heading">
                            <th>#</th>
                            <th>Draft</th>
                            <th>Remarks</th>
                            <th>Status</th>
                            <th class="pagination">
                                <span class="previous" title="Previous"><i class="material-icons">keyboard_arrow_left</i></span>
                                <span class="next" title="Next"><i class="material-icons">keyboard_arrow_right</i></span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="tableData">
                        <!-- POPULATE DATA -->
                    </tbody>
                </table>
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
<script src="{{ asset('assets/js/schedules/vpaa.schedules.draft.js') }}"></script>
@endsection