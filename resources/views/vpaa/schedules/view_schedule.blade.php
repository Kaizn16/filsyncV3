@extends('layouts.app')

@section('content')
    <section class="wrapper">
        <header class="header">
            <h2 class="title">View Schedule Draft</h2>
            <input type="hidden" name="schedule_draft_id" id="schedule_draft_id" value="{{ $scheduleDraft->schedule_draft_id }}">
        </header>

        <div class="content-box">
            <div class="table-container">
                <header class="table-header">
                    <h2 class="title" id="departmentInfo">
                        Schedule Draft
                    </h2>
                    <div class="table-header-actions">
                        <a href="{{ route('vpaa.schedules') }}"><i class="material-icons icon">arrow_left</i>Back</a>
                        @if($scheduleDraft->status != 'approved' && $scheduleDraft->status != 'rejected')
                            <form action="{{ route('vpaa.update.schedule.draft.status', ['schedule_draft_id' => $scheduleDraft->schedule_draft_id]) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" title="APPROVED" class="btn-link">
                                    <i class="material-icons icon">check_circle</i>
                                    APPROVED
                                </button>
                            </form>
                            <button type="button" title="REJECT" class="btn-link" data-schedule_draft_id="{{ $scheduleDraft->schedule_draft_id }}" onclick="event.stopPropagation(); rejectScheduleDraft(this);">
                                <i class="material-icons icon">cancel</i>
                                REJECT
                            </button>
                        @endif
                    </div>
                </header>
                <div class="table-info">
                    <p class="filterInfo"></p>
                    <button class="print" id="printButton" title="PRINT"><i class="material-icons">print</i></button>
                </div> 
                <div class="table-wrapper">
                    <header class="header">
                        <strong>A.Y {{ $scheduleDraft->academic_year }} - {{ $scheduleDraft->semester }}</strong>
                        <strong>{{ $scheduleDraft->course->course_name }}</strong>
                        <strong id="YearLevel-Section-Info">{{ $scheduleDraft->year_level[0] }} - {{ $scheduleDraft->section->name }}</strong>
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
    const BASE_ASSETS_PATH = `{{ asset('assets') }}`;
</script>
<script src="{{ asset('assets/js/schedules/vpaa.schedule.draft.subjects.js') }}"></script>
<script>
    function rejectScheduleDraft(schedule_draft_id) {
        const scheduleDraftId = JSON.parse(schedule_draft_id.getAttribute('data-schedule_draft_id'));

        const UPDATE_DRAFT_STATUS = route(`vpaa.update.schedule.draft.status`, { schedule_draft_id: scheduleDraftId });
        let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        Swal.fire({
            html: `
                <strong class="info">Are you sure you want to reject this schedule draft?</strong>
                <section class="form-wrapper">
                    <div class="form-container">
                        <form id="rejectScheduleForm" class="form-content" action="${UPDATE_DRAFT_STATUS}" method="POST">
                            <input type="hidden" name="_token" value="${csrfToken}">
                            <input type="hidden" name="_method" value="PUT">
                            <div class="form-group">
                                <label for="remarks" class="swal-label">Remarks</label>
                                <textarea id="remarks" name="remarks" placeholder="Enter remarks here..."></textarea>
                            </div>
                        </form>
                    </div>
                </section>
            `,
            confirmButtonText: 'YES',
            confirmButtonColor: '#5296BE',
            showCancelButton: true,
            cancelButtonColor: "#d33",
            cancelButtonText: 'CANCEL',
            reverseButtons: true,
            customClass: {
                popup: 'custom-swal-popup',
            },
            preConfirm: () => {
                const remarks = document.getElementById('remarks').value.trim();
                if (!remarks) {
                    Swal.showValidationMessage('Remarks are required.');
                    return false;
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('rejectScheduleForm').submit();
            }
        });
    }
</script>
@endsection