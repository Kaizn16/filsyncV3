<?php

namespace App\Http\Controllers\Student;

use App\Constants\AppConstants;
use App\Models\AcademicSetting;
use App\Models\Course;
use App\Models\Schedule;
use App\Models\Department;
use App\Models\Section;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function index()
    {
        $departments = Department::all();

        return view('student.index', compact('departments'));
    }

    public function schedules(string $department_abbreviation)
    {
        $department = Department::where('abbreviation', $department_abbreviation)->first();

        if(!$department) 
        {
            return redirect()->route('students')->with([
                'type' => 'warning',
                'message' => 'Department not found!',
            ]);
        }

        $academicSettings = AcademicSetting::first();
        $courses = Course::where('department_id', $department->department_id)->get();      
        $semesters = AppConstants::SEMESTERS;
        $yearLevels = AppConstants::YEAR_LEVELS;
        $sections = Section::all();

        return view('student.schedules', compact('department', 'courses', 'academicSettings', 'semesters', 'yearLevels', 'sections'));
    }

    public function fetchSchedules(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course' => 'required',
            'semester' => 'required|string',
            'year_level' => 'required|string',
            'section' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $academic_year = $request->input('academic_year');
        $semester = $request->input('semester');
        $course = $request->input('course');
        $year_level = $request->input('year_level');
        $section = $request->input('section');

        $query = Schedule::with(['user', 'room', 'course_no', 'section', 'weekdays']);

        if (empty($course) || empty($semester) || empty($year_level) || empty($section)) {
            return response()->json(['error' => 'Missing required parameters'], 400);
        }

        $query->whereHas('scheduleDraft', function ($record) use ($academic_year, $semester, $course, $year_level, $section) {
            $record->where('is_deleted', false)
                ->where('academic_year', $academic_year)
                ->where('semester', $semester)
                ->where('course_id', $course)
                ->where('year_level', $year_level)
                ->where('section_id', $section)
                ->where('status', 'approved');
        });

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
}