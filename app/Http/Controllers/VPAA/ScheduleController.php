<?php

namespace App\Http\Controllers\VPAA;

use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Models\ScheduleDraft;
use App\Constants\AppConstants;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class ScheduleController extends Controller
{
    public function index()
    {

        $academicYears = ScheduleDraft::select('academic_year')->distinct()->get();

        $semesters = AppConstants::SEMESTERS;

        return view('vpaa.schedules.schedules', compact('academicYears', 'semesters', ));
    }

    public function fetchSchedulesDraft(Request $request)
    {
        $statuses = ['pending', 'approved', 'rejected'];

        $validatedData = $request->validate([
            'search' => 'nullable|string',
            'academic_year' => 'nullable|string',
            'semester' => 'nullable|string',
            'status' => ['nullable', Rule::in($statuses)],
        ]);

        $schedulesDraft = ScheduleDraft::where('is_deleted', false)
            ->when($validatedData['search'] ?? null, function ($query, $search) {
                return $query->where('draft_name', 'like', "%$search%");
            })
            ->when($validatedData['academic_year'] ?? null, function ($query, $academicYear) {
                return $query->where('academic_year', $academicYear);
            })
            ->when($validatedData['semester'] ?? null, function ($query, $semester) {
                return $query->where('semester', $semester);
            })
            ->when($validatedData['status'] ?? null, function ($query, $status) {
                return $query->where('status', $status);
            }, function ($query) use ($statuses) {
                return $query->whereIn('status', $statuses);
            })->paginate(10);
        
        if($schedulesDraft) {
            return response()->json($schedulesDraft);
        }

        return response()->json([]);
    }

    public function show(string $schedule_draft_id)
    {
        $scheduleDraft = ScheduleDraft::where('schedule_draft_id', $schedule_draft_id)->first();
        
        if(!$scheduleDraft) {
            return redirect()->route('vpaa.schedules')->with([
                'type' => 'warning',
                'message' => 'Schedule Draft Not Found!'
            ]);
        }

        return view('vpaa.schedules.view_schedule', compact('scheduleDraft'));
    }

    public function fetchScheduleByDraft(Request $request)
    {

        $schedule_draft_id = $request->schedule_draft_id;

        $query = Schedule::with(['user', 'room', 'course_no', 'section', 'weekdays'])
            ->where('schedule_draft_id', $schedule_draft_id);

        $schedules = $query->get();

        if ($schedules->isEmpty()) {
            return response()->json([]);
        }

        $groupedSchedules = [];

        foreach ($schedules as $schedule) {
            $course_no_id = $schedule->course_no_id;
            $teacher_id = $schedule->user ? $schedule->user->user_id : 'TBA';
            
            $groupKey = $course_no_id . '-' . $teacher_id;

            if (!isset($groupedSchedules[$groupKey])) {
                $groupedSchedules[$groupKey] = [
                    'course_no_id' => $schedule->course_no->course_no_id,
                    'course_no' => $schedule->course_no->course_no,
                    'descriptive_title' => $schedule->course_no->descriptive_title,
                    'credits' => $schedule->course_no->credits,
                    'user_id' => $schedule->user ? $schedule->user->user_id : null,
                    'teacher' => $schedule->user ? $schedule->user->last_name : 'TBA',
                    'schedules' => [],
                ];
            }

            $groupedSchedules[$groupKey]['schedules'][] = [
                'schedule_id' => $schedule->schedule_id,
                'weekdays' => $schedule->weekdays->pluck('day')->toArray(),
                'start_time' => $schedule->start_time,
                'end_time' => $schedule->end_time,
                'room_id' => $schedule->room ? $schedule->room->room_id : null,
                'room' => $schedule->room ? $schedule->room->room_name : 'TBA',
            ];
        }

        if(empty($groupedSchedules)) {
            return response()->json([]);
        }

        return response()->json(array_values($groupedSchedules));
    }

    public function updateScheduleDraftStatus(Request $request, string $schedule_draft_id)
    {
        $validatedData = $request->validate([
            'remarks' => 'nullable|string'
        ]);

        $scheduleDraft = ScheduleDraft::find($schedule_draft_id);

        if (!$scheduleDraft) {
            return redirect()->route('vpaa.schedules')->with([
                'type' => 'warning',
                'message' => 'Schedule Draft Not Found!'
            ]);
        }

        $scheduleDraft->status = !empty($validatedData['remarks']) ? 'rejected' : 'approved';
        $scheduleDraft->remarks = !empty($validatedData['remarks']) ? $validatedData['remarks'] : '';
        $scheduleDraft->save();

        return redirect()->route('vpaa.schedules')->with([
            'type' => 'success',
            'message' => 'Schedule Draft Updated Successfully!'
        ]);
    }

}
