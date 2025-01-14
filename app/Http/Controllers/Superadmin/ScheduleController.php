<?php

namespace App\Http\Controllers\Superadmin;

use App\Models\Room;
use App\Models\User;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Schedule;
use App\Models\YearLevel;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Constants\AppConstants;
use App\Http\Controllers\Controller;

class ScheduleController extends Controller
{
    public function index()
    {
        return view("superadmin.schedules.schedules");
    }

    public function subjects(Request $request)
    {
        $department = Department::where('abbreviation', $request->input('department'))->first();

        if (!$department) {
            return redirect()->route('superadmin.schedules');
        }

        $teachers = User::where('department_id', $department->department_id)
                ->where('is_deleted', false)
                ->where(function($query) {
                    $query->where('position', 'TEACHER')
                          ->orWhere('position', 'DEAN');
                })
                ->select('user_id', 'first_name', 'middle_name', 'last_name', 'position')
                ->get();

        $subjects = Subject::where('department_id', $department->department_id);
        $courses = Course::where('department_id', $department->department_id)->get();
        $yearLevels = YearLevel::all();   
        $semesters = AppConstants::SEMESTERS;
        $sections = AppConstants::SECTIONS;
        $weekdays = AppConstants::WEEKDAYS;
        $rooms = Room::where('is_deleted', false)->get();

        return view("superadmin.schedules.subjects_schedule", 
        compact("teachers","department", "courses", "yearLevels", "semesters",  "sections", "weekdays", "rooms"));
    }

    public function fetchSubjectsByCourse(Request $request)
    {
        $courseId = $request->input('course_id');

        $subjects = Subject::with('course_no')->where("course_id", $courseId)
            ->where('is_deleted', false)
            ->get();

        return response()->json($subjects);
    }


    public function fetchSchedules(Request $request)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,department_id',
        ]);

        $schedulesQuery = Schedule::with(['user', 'subject.course_no', 'room', 'year_level'])
            ->where('department_id', $request->input('department_id'));

        if ($search = $request->input('search')) {
            $schedulesQuery->whereHas('user', function ($query) use ($search) {
                $query->where('first_name', 'like', "%$search%")
                    ->orWhere('middle_name', 'like', "%$search%")
                    ->orWhere('last_name', 'like', "%$search%");
            })
            ->orWhereHas('subject.course_no', function ($query) use ($search) {
                $query->where('course_no', 'like', "%$search%")
                    ->orWhere('descriptive_title', 'like', "%$search%");
            });
        }

        if ($course_id = $request->input('course_id')) {
            $schedulesQuery->whereHas('subject.course_no', function ($query) use ($course_id) {
                $query->where('course_id', $course_id);
            });
        }

        if ($year_level_id = $request->input('year_level_id')) {
            $schedulesQuery->where('year_level_id', $year_level_id);
        }

        if ($semester = $request->input('semester')) {
            $schedulesQuery->where('semester', $semester);
        }

        $schedules = $schedulesQuery->paginate(15);

        return response()->json([
            'data' => $schedules->isEmpty() ? null : $schedules,
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'department_id' => 'required|integer',
            'course' => 'required|integer',
            'subject' => 'required|integer',
            'semester' => 'required|string',
            'teacher' => 'nullable|integer',
            'room' => 'required|integer',
            'year_level' => 'required|string',
            'sections' => 'required|array',
            'weekdays' => 'required|array',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $existingSchedule = Schedule::where(function ($query) use ($validatedData) {
            foreach ($validatedData['weekdays'] as $day) {
                $query->orWhere('weekdays', 'LIKE', "%$day%");
            }
            $query->where('start_time', '<=', $validatedData['end_time'])
                ->where('end_time', '>=', $validatedData['start_time']);
        })->where('room_id', $validatedData['room'])
        ->first();

        if ($existingSchedule) {
            return redirect()->back()->with([
                "message" => "A schedule already exists for this room, time, and days.",
                'type' => 'error',
            ])->withInput();
        }

        $duplicateCheck = Schedule::where('subject_id', $validatedData['subject'])
            ->where('year_level_id', $validatedData['year_level'])
            ->whereJsonContains('sections', $validatedData['sections'])
            ->where('room_id', $validatedData['room'])
            ->first();

        if ($duplicateCheck) {
            return redirect()->back()->with([
                "message" => "A schedule already exists for this subject, year level, sections, and room.",
                'type' => 'error',
            ])->withInput();
        }

        Schedule::create([
            'department_id' => $validatedData['department_id'],
            'course_id' => $validatedData['course'],
            'subject_id' => $validatedData['subject'],
            'semester' => $validatedData['semester'],
            'user_id' => $validatedData['teacher'],
            'room_id' => $validatedData['room'],
            'year_level_id' => $validatedData['year_level'],
            'sections' => $validatedData['sections'],
            'weekdays' => $validatedData['weekdays'],
            'start_time' => $validatedData['start_time'],
            'end_time' => $validatedData['end_time'],
        ]);

        return redirect()->back()->with([
            "message" => "Schedule successfully created!",
            'type' => 'success',
        ]);
    }

    public function update(Request $request)
    {
        $scheduleToUpdate = Schedule::where('schedule_id', $request->input('schedule_id'))->first();

        if (!$scheduleToUpdate) {
            return redirect()->back()->with([
                "message" => "Invalid schedule ID provided.",
                'type' => 'error',
            ]);
        }

        $existingSchedule = Schedule::where(function ($query) use ($request) {
            foreach ($request['weekdays'] as $day) {
                $query->orWhere('weekdays', 'LIKE', "%$day%");
            }
            $query->where('start_time', '<=', $request['end_time'])
                ->where('end_time', '>=', $request['start_time']);
        })
        ->where('room_id', $request['room'])
        ->where('schedule_id', '!=', $scheduleToUpdate->schedule_id)
        ->first();

        if ($existingSchedule) {
            return redirect()->back()->with([
                "message" => "A schedule already exists for this room, time, and days.",
                'type' => 'error',
            ])->withInput();
        }

        $duplicateCheck = Schedule::where('subject_id', $request['subject'])
            ->where('year_level_id', $request['year_level'])
            ->whereJsonContains('sections', $request['sections'])
            ->where('room_id', $request['room'])
            ->where('schedule_id', '!=', $scheduleToUpdate->schedule_id)
            ->first();

        if ($duplicateCheck) {
            return redirect()->back()->with([
                "message" => "A schedule already exists for this subject, year level, sections, and room.",
                'type' => 'error',
            ])->withInput();
        }

        $scheduleToUpdate->update([
            'department_id' => $request['department_id'],
            'course_id' => $request['course'],
            'subject_id' => $request['subject'],
            'semester' => $request['semester'],
            'user_id' => $request['teacher'] ?? null,
            'room_id' => $request['room'],
            'year_level_id' => $request['year_level'],
            'sections' => $request['sections'],
            'weekdays' => $request['weekdays'],
            'start_time' => $request['start_time'],
            'end_time' => $request['end_time'],
        ]);

        return redirect()->back()->with([
            "message" => "Schedule successfully updated!",
            'type' => 'success',
        ]);
    }





    public function bulkDelete(Request $request)
    {
        $schedule_ids = $request->input('schedule_ids');

        if (!empty($schedule_ids)) {
            Schedule::whereIn('schedule_id', $schedule_ids)->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Schedule deleted successfully.'
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'No schedule selected for deletion.'
        ], 400);
    }

    public function delete(Request $request)
    {
        $schedule_id = $request->input('schedule_id');

        if (!empty($schedule_id)) {
            Schedule::where('schedule_id', $schedule_id)->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Schedule deleted successfully.'
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'No schedule selected for deletion.'
        ], 400);
    }


}
