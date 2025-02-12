<?php

namespace App\Http\Controllers\Admin;

use App\Constants\AppConstants;
use App\Http\Controllers\Controller;
use App\Models\AcademicSetting;
use App\Models\Course;
use App\Models\ScheduleDraft;
use App\Models\ScheduleWeekday;
use App\Models\Section;
use Auth;
use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\User;
use App\Models\Schedule;

class RoomController extends Controller
{
    public function index()
    {   
        $dean = User::with('department')->where('user_id', Auth::user()->user_id)->first();
        $department = $dean->department->abbreviation;
        $academic_years = ScheduleDraft::distinct('academic_year')->pluck('academic_year');
        $academicSetting = AcademicSetting::first();
        $semesters = AppConstants::SEMESTERS;
        $yearLevels = AppConstants::YEAR_LEVELS;
        $sections = Section::all();

        $schedule_drafts = ScheduleDraft::where('user_id', Auth::user()->user_id)
            ->where('department_id', Auth::user()->department_id)
            ->get();

        $schedule_draft_ids = $schedule_drafts->pluck('schedule_draft_id')->toArray();

        $schedules = Schedule::whereIn('schedule_draft_id', $schedule_draft_ids)->get();

        $schedule_ids = $schedules->pluck('schedule_id')->toArray();

        $scheduleWeekdays = ScheduleWeekday::whereIn('schedule_id', $schedule_ids)
            ->orderByRaw("
                CASE 
                    WHEN day = 'Monday' THEN 1 
                    WHEN day = 'Tuesday' THEN 2 
                    WHEN day = 'Wednesday' THEN 3 
                    WHEN day = 'Thursday' THEN 4 
                    WHEN day = 'Friday' THEN 5 
                    WHEN day = 'Saturday' THEN 6 
                    WHEN day = 'Sunday' THEN 7 
                END
            ")
            ->get()
            ->groupBy('schedule_id')
            ->map(function ($weekdays) {
                $days = $weekdays->pluck('day')->unique()->values()->toArray();
                return [
                    'schedule_id' => $weekdays->first()->schedule_id,
                    'days' => $days
                ];
            })
            ->values()
            ->unique(function ($schedule) {
                return implode(',', $schedule['days']);
            })
            ->values();
        
        $rooms = Room::where('is_deleted', false)->get(['room_id', 'room_name']);
        $weekdays = AppConstants::WEEKDAYS;

        return view('admin.rooms', compact('department','academicSetting', 'academic_years', 'semesters', 'yearLevels', 'sections', 'scheduleWeekdays', 'rooms', 'weekdays'));
    }

    public function fetchSchedules(Request $request)
    {
        $schedule_drafts = ScheduleDraft::where('user_id', Auth::user()->user_id)
            ->where('department_id', Auth::user()->department_id)
            ->get();
        
        
            $schedule_draft_ids = $schedule_drafts->pluck('schedule_draft_id')->toArray();

            $schedules = Schedule::with(['user', 'room', 'course_no', 'scheduleDraft.section', 'weekdays'])
                ->whereIn('schedule_draft_id', $schedule_draft_ids)
                ->whereHas('scheduleDraft', function ($query) use ($request) {
                    $query->where('academic_year', $request->input('academic_year'))
                        ->where('semester', $request->input('semester'))
                        ->whereIn('status', ['saved', 'approved'])
                        ->where('is_deleted', false);
                })
                ->when($request->filled('weekdays'), function ($query) use ($request) {
                    $selectedDays = explode(', ', $request->input('weekdays'));
            
                    $query->whereHas('weekdays', function ($q) use ($selectedDays) {
                        $q->whereIn('day', $selectedDays);
                    }, '=', count($selectedDays));
                })
                ->orderBy('end_time')
                ->orderBy('start_time')
                ->get();
            

        return response()->json($schedules);
    }


    public function editSchedule(Request $request)
    {
        $schedule = Schedule::with(['weekdays', 'course_no'])->where('schedule_id', $request->input('schedule_id'))->first();

        if ($schedule) {
            return response()->json($schedule);
        }
        return response()->json(['error' => 'Schedule not found'], 404);

    }

    public function updateSchedule(Request $request)
    {
        $request->merge([
            'start_time' => date('H:i', strtotime($request->start_time)),
            'end_time' => date('H:i', strtotime($request->end_time)),
        ]);

        $request->validate([
            'schedule_draft_id' => 'required|integer',
            'room' => 'required|integer',
            'weekdays' => 'required|array|min:1',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $schedule = Schedule::findOrFail($request->input('schedule_id'));

        $storedWeekdays = collect($schedule->weekdays->pluck('day'))->sort()->values()->toArray();
        $requestWeekdays = collect($request->weekdays)->sort()->values()->toArray();

        $hasChanges = (int) $schedule->schedule_draft_id !== (int) $request->schedule_draft_id ||
            (int) $schedule->room_id !== (int) $request->room ||
            date('H:i', strtotime($schedule->start_time)) !== $request->start_time ||
            date('H:i', strtotime($schedule->end_time)) !== $request->end_time ||
            $storedWeekdays !== $requestWeekdays;

        if (!$hasChanges) {
            return response()->json([
                'success' => true,
                'message' => 'No changes detected.'
            ]);
        }

        $conflictingSchedule = Schedule::where('room_id', $request->room)
            ->whereHas('scheduleDraft', function ($query) {
                $query->whereIn('status', ['saved', 'pending', 'approved'])
                    ->where('is_deleted', false);
            })
            ->whereHas('weekdays', function ($query) use ($request) {
                $query->whereIn('day', $request->weekdays);
            })
            ->where('schedule_id', '!=', $schedule->schedule_id)
            ->where(function ($query) use ($request) {
                $query->where('start_time', '<', $request->end_time)
                    ->where('end_time', '>', $request->start_time);
            })
            ->where('schedule_id', '!=', $schedule->schedule_id)
            ->exists();

        if ($conflictingSchedule) {
            return response()->json([
                'success' => false,
                'message' => 'Schedule conflict detected for the same room, time, and day.'
            ]);
        }

        $schedule->update([
            'room_id' => $request->room,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        $schedule->weekdays()->delete();
        foreach ($request->weekdays as $day) {
            ScheduleWeekday::create([
                'schedule_id' => $schedule->schedule_id,
                'day' => $day,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Schedule successfully updated']);
    }

}
