@extends('layouts.app')

@section('content')
    <section class="wrapper">

        <div class="statwidgets">
            <div class="statwidget-item ">
                <i class="material-icons icon">drafts</i>
                <div class="stat-info">
                    <strong class="title">Submitted Schedule Drafts</strong>
                    <strong class="subtitle">{{ $totalSubmittedScheduleDrafts ?? 0 }}</strong>
                </div>
            </div>

            <div class="statwidget-item ">
                <i class="material-icons icon">pending</i>
                <div class="stat-info">
                    <strong class="title">Pending Schedule Drafts</strong>
                    <strong class="subtitle">{{ $totalPendingScheduleDrafts ?? 0 }}</strong>
                </div>
            </div>

            <div class="statwidget-item ">
                <i class="material-icons icon">check</i>
                <div class="stat-info">
                    <strong class="title">Approved Schedule Drafts</strong>
                    <strong class="subtitle">{{ $totalApprovedScheduleDrafts ?? 0 }}</strong>
                </div>
            </div>

            <div class="statwidget-item ">
                <i class="material-icons icon">clear</i>
                <div class="stat-info">
                    <strong class="title">Rejected Schedule Drafts</strong>
                    <strong class="subtitle">{{ $totalRejectedScheduleDrafts ?? 0 }}</strong>
                </div>
            </div>
        </div>

        <div class="boxes">
            <div class="box history">
                <strong class="title"><i class="material-icons icon">history</i> Schedule Draft History</strong>
                <ul class="lists">
                    @foreach ($schedulesRecently as $index => $schedule)
                        <li class="item-info">
                            <strong>{{ $index + 1 }}.</strong>
                            <strong>{{ $schedule->user->first_name }} {{ $schedule->user->last_name }} | </strong>
                            <strong>{{ $schedule->department->abbreviation }} - {{ $schedule->course->abbreviation }}</strong>
                            <strong>{{ $schedule->year_level }} - {{ $schedule->section->name }}</strong>
                            <strong>{{ $schedule->academic_year }} - {{ $schedule->semester }}</strong>
                            <strong>{{ $schedule->created_at->format('F j g:i A') }}</strong>
                            <strong class="status {{ $schedule->status }}">{{ $schedule->status }}</strong>
                            <a href="{{ route('vpaa.view.schedules', ['schedule_draft_id' => $schedule->schedule_draft_id] ) }}" title="VIEW"><i class="material-icons icon">visibility</i>VIEW</a>
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