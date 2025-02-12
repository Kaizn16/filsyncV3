@extends('layouts.app')

@section('content')
    <section class="wrapper">
        <header class="header">
            <h2 class="title">Schedule Management</h2>
            <input type="hidden" name="department" id="department" value="{{ $department }}">
            <input type="hidden" name="course" id="course" value="{{ $course }}">
        </header>

        <div class="content-box">
            <div class="table-container">
                <heder class="table-header">
                    <h2 class="title">
                        My Drafts
                    </h2>
                    <div class="table-header-actions">
                        <a href="{{ route('admin.course.schedule', ['department' => $department, 'course' => $course]) }}"><i class="material-icons icon">arrow_left</i>Back</a>
                        <div class="form-group">
                            <label for="showDeletedData" class="showDeletedData">Show Deleted Drafts</label>
                            <input type="checkbox" name="showDeletedData" id="showDeletedData" title="Show Deleted Drafts">
                        </div>
                    </div>
                </heder>
                <table class="table">
                    <thead>
                        <tr class="heading">
                            <th>#</th>
                            <th>Draft</th>
                            <th>Status</th>
                            <th></th>
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
<script>
    const base_route = "{{ asset('assets/css/main.css') }}";
</script>
<script src="{{ asset('assets/js/schedules/schedule.mydraft.js') }}"></script>
@endsection