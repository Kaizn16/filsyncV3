<?php

namespace App\Http\Controllers\Api;

use App\Models\Course;
use App\Models\CoursesNo;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DepartmentController extends Controller
{
    public function fetchDepartments()
    {
        $position = request()->query('position');
        $searchTerm = request()->query('search');
        $department_id = Auth::user()->department_id;


        if ($position !== 'VPAA' && $position !== 'REGISTRAR') {
            $query = Department::query();

            if (!empty($department_id)) {
                $query->where('department_id', $department_id);
            }
    
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


    public function fetchCoursesByDepartment(Request $request)
    {
        $courses = Course::where('department_id', $request->input('department_id'))->get();
        return response()->json($courses);
    }

    public function fetchCourseNo(Request $request)
    {
        $course_no = CoursesNo::where('course_id', $request->input('course_id'))->get();
        return response()->json($course_no);
    }

    public function fetchCourseNoDetails(Request $request)
    {
        $course_no = CoursesNo::find( $request->input('course_no_id') );
        return response()->json($course_no);
    }
}
