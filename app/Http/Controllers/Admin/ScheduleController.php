<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Session;
use Carbon\Carbon;
use App\Models\Room;
use App\Models\User;
use App\Models\Course;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Schedule;
use App\Models\CoursesNo;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\ScheduleDraft;
use App\Constants\AppConstants;
use App\Models\AcademicSetting;
use App\Models\ScheduleWeekday;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
{
    public function index()
    {
        return view("admin.schedules.departments");
    }

    public function fetchDepartments()
    {
        $position = request()->query('position');
        $searchTerm = request()->query('search');

        if ($position !== 'VPAA' && $position !== 'REGISTRAR') {
            $query = Department::query();
    
            if (!empty($searchTerm)) {
                $query->where(function ($query) use ($searchTerm) {
                    $query->where('department_name', 'LIKE', '%' . $searchTerm . '%')
                          ->orWhere('abbreviation', 'LIKE', '%' . $searchTerm . '%');
                });
            }

            $departments = $query->get();

            return response()->json($departments);
        }

        return response()->json([]);
    }

    public function departmentCourses(string $department) 
    {   
        $department_id = Department::where('abbreviation', $department)->value('department_id');

        if (empty($department_id)) {
            return redirect()->back()->with([
                "message" => "Department not found.",
                'type' => 'error',
            ])->withInput();
        }

        $courses = Course::where('department_id', $department_id)->get();

        return view('admin.schedules.courses', compact('department', 'courses'));

    }

    public function courseSchedules(string $department, string $course)
    {
        if (!$department && !$course) {
            return redirect()->back();
        }
        
        $teacher = User::with('department')->where('user_id', Auth::user()->user_id)->first();

        $scheduleDraft = ScheduleDraft::where('user_id', Auth::user()->user_id)
                                       ->where('status', 'draft')
                                       ->latest()
                                       ->first();
        
        $semesters = AppConstants::SEMESTERS;
        $yearLevels = AppConstants::YEAR_LEVELS;
        $sections = Section::all();
        $academicSetting = AcademicSetting::first();
    
        return view('admin.schedules.schedules', compact('teacher','scheduleDraft', 'department', 'course', 'semesters', 'yearLevels', 'sections', 'academicSetting'));
    }
    
    public function create(Request $request, string $department, string $course) 
    {
        if(!$department && !$course)
        {
            return redirect()->back();
        }

        $department = Department::where('abbreviation', $department)->first();
        $course = Course::where('abbreviation', $course)->first();
        $currentScheduleDraft = ScheduleDraft::where('user_id', Auth::user()->user_id)
            ->where('status', 'draft')->latest()->first();
        
        if ($currentScheduleDraft) {
            $schedule_draft_id = $currentScheduleDraft->schedule_draft_id;
            $year_level = $currentScheduleDraft->year_level;
            $section = $currentScheduleDraft->section;
            $semester = $currentScheduleDraft->semester;
            $academic_year = $currentScheduleDraft->academic_year;
        } else 
        {
            $sectionName = Section::where('section_id', $request->input('section'))->first();

            $scheduleDraft = ScheduleDraft::create([
                'department_id' => $department->department_id,
                'course_id' => $course->course_id,
                'user_id' => Auth::user()->user_id,
                'draft_name' => $department->abbreviation . '-' . $course->abbreviation . '-' . $request->input('year_level') . '-' . $sectionName->name . '-' . $request->input('semester') . '-' . $request->input('academic_year') . ' ' . Carbon::now()->toDayDateTimeString(),
                'year_level' => $request->input('year_level'),
                'section_id' => $request->input('section'),
                'semester' => $request->input('semester'),
                'academic_year' => $request->input('academic_year'),
            ]);
            
            $schedule_draft_id = $scheduleDraft->schedule_draft_id;
            $year_level = $scheduleDraft->year_level;
            $section = $scheduleDraft->section;
            $semester = $scheduleDraft->semester;
            $academic_year = $scheduleDraft->academic_year;
        }
    
        $teachers = User::where('department_id', $department->department_id)->where('position', 'TEACHER')->get();
        $rooms = Room::where('is_deleted', false)->get(['room_id', 'room_name']);
        $weekdays = AppConstants::WEEKDAYS;
        $action = 'create';

        return view('admin.schedules.schedule_draft', compact('schedule_draft_id','department', 'course', 'year_level', 'section', 'semester', 'academic_year', 'teachers', 'rooms', 'weekdays', 'action'));
    }

    public function view(string $department, string $course)
    {
        $myScheduleDrafts = ScheduleDraft::where('user_id', Auth::user()->user_id)
        ->orderBy('created_at', 'desc')
        ->get();
    
        return view('admin.schedules.view_drafts', compact('department', 'course', 'myScheduleDrafts'));
    }

    public function edit(Request $request, string $department, $course, $schedule_draft_id)
    {

        if(!$department && !$course)
        {
            return redirect()->back();
        }

        $department = Department::where('abbreviation', $department)->first();
        $course = Course::where('abbreviation', $course)->first();
        $scheduleDraft = ScheduleDraft::where('schedule_draft_id', $schedule_draft_id)->first();

        $schedule_draft_id = $scheduleDraft->schedule_draft_id;
        $academic_year = $scheduleDraft->academic_year;
        $semester = $scheduleDraft->semester;
        $year_level = $scheduleDraft->year_level;
        $section = $scheduleDraft->section;
        $teachers = User::where('department_id', $department->department_id)->where('position', 'TEACHER')->get();
        $rooms = Room::where('is_deleted', false)->get(['room_id', 'room_name']);
        $weekdays = AppConstants::WEEKDAYS;
        $action = 'edit';

        return view('admin.schedules.schedule_draft', compact('schedule_draft_id','department', 'course', 'academic_year', 'semester', 'year_level', 'section', 'teachers', 'rooms', 'weekdays', 'action'));
    }

    public function cancelScheduleDraft(Request $request, string $department, string $course)
    {
        $schedule_draft = ScheduleDraft::find($request->input('schedule_draft_id'));
        $schedule_draft->delete();
        session()->forget('schedule_draft_id');
    
        return redirect()->route('admin.course.schedule', compact('department', 'course'))
            ->with([
                "message" => "Schedule Draft Has Been Cancelled.",
                'type' => 'info',
            ]);
    }

    public function savedScheduleDraft(string $department, string $course, string $schedule_draft_id)
    {
        $schedule_draft = ScheduleDraft::find($schedule_draft_id);

        $schedule_draft->status = 'saved';

        if($schedule_draft->save()) 
        {
            session()->forget('schedule_draft_id');
            return redirect()->route('admin.course.schedule', compact('department', 'course'))
            ->with([
                "message" => "Schedule draft has been saved.",
                'type' => 'success',
            ]);
        }

        return redirect()->route('admin.course.schedule', compact('department', 'course'))
            ->with([
                "message" => "Unable to save draft.",
                'type' => 'error',
            ]);
    }

    public function storeSchedule(Request $request) 
    {
        
        $request->validate([
            'schedule_draft_id' => 'required|integer',
            'course_no_id' => 'required|integer',
            'teacher' => 'nullable|integer',
            'room' => 'required|integer',
            'weekdays' => 'required|array|min:1',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $conflictingSchedule = Schedule::where('room_id', $request->room)
            ->whereHas('scheduleDraft', function ($query) use ($request) {
                $query->where('academic_year', $request->academic_year)
                    ->where('semester', $request->semester)
                    ->whereIn('status', ['saved', 'pending', 'approved'])
                    ->where('is_deleted', false);
            })
            ->whereHas('weekdays', function ($query) use ($request) {
                $query->whereIn('day', $request->weekdays);
            })
            ->where(function ($query) use ($request) {
                $query->where('start_time', '<', $request->end_time)
                    ->where('end_time', '>', $request->start_time);
            })
            ->exists();

        if ($conflictingSchedule) {
            return response()->json([
                'success' => false,
                'message' => 'Schedule conflict detected for the same room, time, and day within the specified academic year and semester.'
            ]);
        }


        $schedule = Schedule::create([
            'schedule_draft_id' => $request->schedule_draft_id,
            'course_no_id' => $request->course_no_id,
            'user_id' => $request->teacher ?? null,
            'room_id' => $request->room,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        if($schedule) {
            foreach ($request->weekdays as $day) {
                ScheduleWeekday::create([
                    'schedule_id' => $schedule->schedule_id,
                    'day' => $day,
                ]);
            }
        }

        return response()->json(['success' => true, 'message' => 'Schedule for successfully created']);
    }

    public function updateSchedule(Request $request)
    {
        $request->merge([
            'start_time' => date('H:i', strtotime($request->start_time)),
            'end_time' => date('H:i', strtotime($request->end_time)),
        ]);

        $request->validate([
            'schedule_draft_id' => 'required|integer',
            'course_no_id' => 'required|integer',
            'teacher' => 'nullable|integer',
            'room' => 'required|integer',
            'weekdays' => 'required|array|min:1',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $schedule = Schedule::findOrFail($request->input('schedule_id'));

        $hasChanges = $schedule->schedule_draft_id !== $request->schedule_draft_id ||
            $schedule->course_no_id !== $request->course_no_id ||
            $schedule->user_id !== ($request->teacher ?? null) ||
            $schedule->room_id !== $request->room ||
            $schedule->start_time !== $request->start_time ||
            $schedule->end_time !== $request->end_time ||
            !empty(array_diff($request->weekdays, $schedule->weekdays->pluck('day')->toArray()));

        if ($hasChanges) {
            $conflictingSchedule = Schedule::where('room_id', $request->room)
                ->whereHas('scheduleDraft', function ($query) use ($request) {
                    $query->where('academic_year', $request->academic_year)
                        ->where('semester', $request->semester)
                        ->whereIn('status', ['saved', 'pending', 'approved'])
                        ->where('is_deleted', false);
                })
                ->whereHas('weekdays', function ($query) use ($request) {
                    $query->whereIn('day', $request->weekdays);
                })
                ->where(function ($query) use ($request) {
                    $query->where('start_time', '<', $request->end_time)
                        ->where('end_time', '>', $request->start_time);
                })
                ->exists();

            if ($conflictingSchedule) {
                return response()->json([
                    'success' => false,
                    'message' => 'Schedule conflict detected for the same room, time, and day within the specified academic year and semester.'
                ]);
            }

            $schedule->update([
                'schedule_draft_id' => $request->schedule_draft_id,
                'course_no_id' => $request->course_no_id,
                'user_id' => $request->teacher ?? null,
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
        }

        return response()->json(['success' => true, 'message' => 'Schedule successfully updated']);
    }

    public function deleteSelectedSchedule(Request $request)
    {
        $schedule = Schedule::find($request->input('schedule_id'));

        if( $schedule->delete()) {
            return response()->json(['success' => true, 'message' => 'Selected schedule has been deleted']);
        }

        return response()->json(['success' => false, 'message' => 'Unable to delete selected schedule']);
    }

    public function fetchSubjects(Request $request)
    {
        $validated = $request->validate([
            'search' => 'nullable|string|max:255',
            'course_id' => 'nullable|integer|exists:courses,course_id',
            'year_level' => 'nullable|string',
            'semester' => 'nullable|string',
        ]);
    
        $search = $validated['search'] ?? null;
        $courseId = $validated['course_id'] ?? null;
        $yearLevel = $validated['year_level'] ?? null;
        $semester = $validated['semester'] ?? null;

        $subjects = CoursesNo::selectRaw('MIN(course_no_id) as course_no_id, course_no, descriptive_title, credits, year_level, semester')
            ->when($courseId, function ($query) use ($courseId) {
                return $query->where('course_id', $courseId);
            })
            ->when($search, function ($query) use ($search) {
                $trimmedSearch = preg_replace('/\s+/', '', $search);
                return $query->where(function($query) use ($trimmedSearch) {
                    $query->where(DB::raw("REPLACE(course_no, ' ', '')"), 'like', "%$trimmedSearch%")
                        ->orWhere(DB::raw("REPLACE(descriptive_title, ' ', '')"), 'like', "%$trimmedSearch%");
                });
            })
            ->when($yearLevel, function ($query) use ($yearLevel) {
                return $query->where('year_level', $yearLevel);
            })
            ->when($semester, function ($query) use ($semester) {
                return $query->where('semester', $semester);
            })
            ->groupBy('course_no', 'descriptive_title', 'credits', 'year_level', 'semester')
            ->paginate(15);


        return response()->json($subjects);
    }

    public function fetchScheduleByDraft(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'schedule_draft_id' => 'nullable|integer',
            'academic_year' => 'nullable|string',
            'year_level' => 'nullable|string',
            'semester' => 'nullable|string',
            'section' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $schedule_draft_id = $request->input('schedule_draft_id');
        $academic_year = $request->input('academic_year');
        $year_level = $request->input('year_level');
        $semester = $request->input('semester');
        $section = $request->input('section');

        $query = Schedule::with(['user', 'room', 'course_no', 'section', 'weekdays']);

        if ($schedule_draft_id) {
            $query->where('schedule_draft_id', $schedule_draft_id);
        } else {
            if (empty($academic_year) || empty($semester) || empty($year_level) || empty($section)) {
                return response()->json(['error' => 'Missing required parameters'], 400);
            }

            $query->whereHas('scheduleDraft', function ($record) use ($academic_year, $year_level, $semester, $section) {
                $record->where('is_deleted', false)
                    ->where('academic_year', $academic_year)
                    ->where('semester', $semester)
                    ->where('year_level', $year_level)
                    ->where('section_id', $section);
            });
        }

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


    public function fetchMyScheduleDrafts(Request $request)
    {
        $isDeleted = filter_var($request->input('isDeleted'), FILTER_VALIDATE_BOOLEAN);

        $myScheduleDrafts = ScheduleDraft::where('user_id', Auth::user()->user_id)
            ->when($isDeleted !== null, function ($query) use ($isDeleted) {
                return $query->where('is_deleted', $isDeleted);
            })->get();

        if($myScheduleDrafts) {
            return response()->json($myScheduleDrafts);    
        }

        return response()->json([]);
    }

    public function restoreMyDraft(Request $request) 
    {
        $myScheduleDraft = ScheduleDraft::where('schedule_draft_id', $request->input('schedule_draft_id'))->first();
    
        if (!$myScheduleDraft || !$myScheduleDraft->is_deleted) {
            return response()->json([
                'success' => false, 
                'message' => 'Draft not found or already restored'
            ]);
        }
    

        $scheduleExists = Schedule::where('schedule_draft_id', $myScheduleDraft->schedule_draft_id)->first();
    
        if ($scheduleExists) {
            
            $conflictingSchedule = Schedule::where('room_id', $scheduleExists->room_id)
                ->whereHas('scheduleDraft', function ($query) use ($myScheduleDraft) {
                    $query->where('academic_year', $myScheduleDraft->academic_year)
                        ->where('semester', $myScheduleDraft->semester)
                        ->whereIn('status', ['saved', 'pending', 'approved'])
                        ->where('is_deleted', false);
                })
                ->whereHas('weekdays', function ($query) use ($scheduleExists) {
                    $weekdays = $scheduleExists->weekdays->pluck('day')->toArray();
                    $query->whereIn('day', $weekdays);
                })
                ->where(function ($query) use ($scheduleExists) {
                    $query->where('start_time', '<', $scheduleExists->end_time)
                        ->where('end_time', '>', $scheduleExists->start_time);
                })
                ->exists();
    
            if ($conflictingSchedule) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot restore draft: conflict detected in the school year, semester, room, time, or day.'
                ]);
            }
        }
    
        $myScheduleDraft->is_deleted = false;
    
        if ($myScheduleDraft->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Selected draft has been restored'
            ]);
        }
    
        return response()->json([
            'success' => false,
            'message' => 'Unable to restore selected draft'
        ]);
    }
    
    public function deleteMyDraft(Request $request) 
    {
        $myScheduleDraft = ScheduleDraft::where('schedule_draft_id', $request->input('schedule_draft_id'))->first();

        if($myScheduleDraft->is_deleted == 1) {
            $myScheduleDraft->delete();
            return response()->json(['success' => true, 'message' => 'Selected draft has been deleted permanently']);
        }


        $myScheduleDraft->is_deleted = true;

        if($myScheduleDraft->save()) {
            return response()->json(['success' => true, 'message' => 'Selected draft has been deleted']);
        }

        return response()->json(['success' => false, 'message' => 'Unable to delete selected draft']);
    }

    public function sendScheduleDraftToVPAA(Request $request)
    {
        $scheduleDraft = ScheduleDraft::where('schedule_draft_id', $request->input('schedule_draft_id'))->first();

        $scheduleDraft->status = 'pending';

        if($scheduleDraft->save())
        {
            return response()->json(['success' => true, 'message' => 'Selected schedule draft successfully send to VPAA for review!']);
        }

        return response()->json(['success' => false, 'message' => 'Unable to send selected schedule draft to VPAA']);
    }

    public function showSelectedScheduleDraft(Request $request)
    {
        $scheduleDraft = ScheduleDraft::with(['course', 'section'])->where('schedule_draft_id', $request->input('schedule_draft_id'))->first();

        $schedule_draft_id = $scheduleDraft->schedule_draft_id;

        $query = Schedule::with(['user', 'room', 'course_no', 'section', 'weekdays']);

        if ($schedule_draft_id) {
            $query->where('schedule_draft_id', $schedule_draft_id);
        }

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

        return response()->json([
            'scheduleDraft' => $scheduleDraft,
            'schedules' => array_values($groupedSchedules)
        ]);
    }
}