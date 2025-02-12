<?php

namespace App\Http\Controllers\Superadmin;

use App\Models\Course;
use App\Models\Subject;
use App\Models\CoursesNo;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Constants\AppConstants;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AcademicController extends Controller
{
    protected $academicYears;

    public function __construct()
    {
        $this->academicYears = $this->getAcademicYears();
    }

    private function getAcademicYears()
    {
        $academic_years = [
            '2000-2001', '2001-2002', '2002-2003', '2003-2004', '2004-2005', '2005-2006', 
            '2006-2007', '2007-2008', '2008-2009', '2009-2010', '2010-2011', '2011-2012', 
            '2012-2013', '2013-2014', '2014-2015', '2015-2016', '2016-2017', '2017-2018', 
            '2018-2019', '2019-2020', '2020-2021', '2021-2022', '2022-2023', '2023-2024', 
            '2024-2025', '2025-2026', '2026-2027', '2027-2028', '2028-2029', '2029-2030', 
            '2030-2031', '2031-2032', '2032-2033', '2033-2034', '2034-2035', '2035-2036', 
            '2036-2037', '2037-2038', '2038-2039', '2039-2040', '2040-2041', '2041-2042', 
            '2042-2043', '2043-2044', '2044-2045', '2045-2046', '2046-2047', '2047-2048', 
            '2048-2049', '2049-2050', '2050-2051'
        ];
        
        return array_map(function($year) {
            return ['academic_year' => $year];
        }, $academic_years);
    }

    public function index()
    {
        return view("superadmin.academics.academics");
    }
    
    public function subjects(Request $request) 
    {   

        $department = Department::where('abbreviation', $request->input('department'))->first();
        $courses = Course::where('department_id', $department->department_id)
            ->where('is_deleted', false)
            ->get();

        $yearLevels = AppConstants::YEAR_LEVELS;
        $semesters = AppConstants::SEMESTERS;
        $academic_years = $this->academicYears;


        return view("superadmin.academics.subjects", compact("department", "courses", 'yearLevels', 'semesters', 'academic_years'));
    }



    public function fetchSubjects(Request $request)
    {
        $department_id = $request->input('department_id');
        $paginate = $request->input('paginate', 10);

        if (!$department_id) {
            return redirect()->route('superadmin.academics');
        }

        $query = CoursesNo::with('course')->where('department_id', $department_id);
        
        if ($request->has('course') && $request->input('course') !== '' && !empty($request->input('course'))) {
            $query->where('course_id', $request->input('course'));
        }

        if ($request->has('year_level') && $request->input('year_level') !== '' && !empty($request->input('year_level'))) {
            $query->where('year_level', $request->input('year_level'));
        }

        if ($request->has('semester') && $request->input('semester') !== '' && !empty($request->input('semester'))) {
            $query->where('semester', $request->input('semester'));
        }

        if ($request->has('search') && !empty($request->input('search'))) {
            $searchTerms = explode(' ', $request->input('search'));
        
            $query->where(function ($q) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $q->where(function ($subQuery) use ($term) {
                        $subQuery->where('course_no', 'like', '%' . $term . '%')
                                 ->orWhere('descriptive_title', 'like', '%' . $term . '%')
                                 ->orWhere('year_level', 'like', '%' . $term . '%')
                                 ->orWhere('semester', 'like', '%' . $term . '%')
                                 ->orWhereHas('course', function ($courseQuery) use ($term) {
                                     $courseQuery->where('course_name', 'like', '%' . $term . '%')
                                                 ->orWhere('abbreviation', 'like', '%' . $term . '%');
                                 });
                    });
                }
            });
        }
        

        $subjects = $paginate === '' ? $query->limit(1000)->get() : $query->paginate($paginate);

        return response()->json($subjects);
    }


    public function store(Request $request)
    {
        $department = Department::where('department_id', $request->input('department_id'))->first();

        $subject_exist = DB::table('subjects')
            ->where('course_no_id', $request->input('course_no'))
            ->where('year_level_id', $request->input('year_level'))
            ->where('semester', $request->input('semester'))
            ->where('academic_year', $request->input('academic_year'))
            ->where('is_deleted', false)
            ->exists();

        if ($subject_exist) {
            return redirect()->route('superadmin.academics.subjects', ['department' => $department->abbreviation])->with([
                'message' => 'Course already exists in the specified year level, semester, and academic year!',
                'type' => 'error'
            ]);
        }
        
        $validate_data = $request->validate([
            'department_id' => 'required|exists:departments,department_id',
            'course_id' => 'required|exists:courses,course_id',
            'course_no' => 'required|exists:courses_no,course_no_id',
            'semester' => 'required|string',
            'year_level' => 'required',
            'academic_year' => 'required',
        ]);

        $subject = new Subject($validate_data);
        $subject->course_no_id = $request->input('course_no');
        $subject->year_level_id = $request->input('year_level');

        if($subject->save()) {
            return redirect()->route('superadmin.academics.subjects',['department' => $department->abbreviation] )->with([
                'message' => 'Subject created successfully!',
                'type' => 'success'
            ]);
        }

        return redirect()->route('superadmin.academics.subjects',['department' => $department->abbreviation] )->with([
            'message' => 'Unable to create subject!',
            'type' => 'error'
        ]);
    }

    public function update(Request $request)
    {
        $subject = Subject::find($request->input('subject_id'));
        $department = Department::where('department_id', $request->input('department_id'))->first();

        if (
            $subject->course_no_id == $request->input('course_no') &&
            $subject->year_level_id == $request->input('year_level') &&
            $subject->semester == $request->input('semester') &&
            $subject->academic_year == $request->input('academic_year')
        ) {
            return redirect()->route('superadmin.academics.subjects', ['department' => $department->abbreviation])->with([
                'message' => 'No changes detected. Subject data is already up to date!',
                'type' => 'info'
            ]);
        }

        $subject_exist = DB::table('subjects')
            ->where('course_no_id', $request->input('course_no'))
            ->where('year_level_id', $request->input('year_level'))
            ->where('semester', $request->input('semester'))
            ->where('academic_year', $request->input('academic_year'))
            ->where('is_deleted', false)
            ->exists();

        if ($subject_exist) {
            return redirect()->route('superadmin.academics.subjects', ['department' => $department->abbreviation])->with([
                'message' => 'Course already exists in the specified year level, semester, and academic year!',
                'type' => 'error'
            ]);
        }

        $request->validate([
            'department_id' => 'required|exists:departments,department_id',
            'course_id' => 'required|exists:courses,course_id',
            'course_no' => 'required|exists:courses_no,course_no_id',
            'semester' => 'required|string',
            'year_level' => 'required',
            'academic_year' => 'required',
        ]);

        $updated_data = $request->only([
            'department_id',
            'course_id',
            'course_no',
            'year_level',
            'semester',
            'academic_year'
        ]);

        if ($subject->update($updated_data)) {
            return redirect()->route('superadmin.academics.subjects', ['department' => $department->abbreviation])->with([
                'message' => 'Subject updated successfully!',
                'type' => 'success'
            ]);
        }

        return redirect()->route('superadmin.academics.subjects', ['department' => $department->abbreviation])->with([
            'message' => 'Unable to update subject!',
            'type' => 'error'
        ]);
    }


    public function bulkDelete(Request $request)
    {
        $subject_ids = $request->input('subject_ids');

        if (!empty($subject_ids)) {
            Subject::whereIn('subject_id', $subject_ids)->update(['is_deleted' => true]);

            return response()->json([
                'status' => 'success',
                'message' => 'Subjects deleted successfully.'
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'No subjects selected for deletion.'
        ], 400);
    }

}
