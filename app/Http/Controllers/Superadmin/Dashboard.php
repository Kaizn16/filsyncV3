<?php

namespace App\Http\Controllers\Superadmin;

use App\Models\User;
use App\Models\Course;
use App\Models\Schedule;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Dashboard extends Controller
{
    public function index()
    {
        $totalUsers = User::where('is_deleted', false)->count();
        $totalDepartments = Department::count();
        $totalCourses = Course::count();
        $totalSchedules = Schedule::count();

        return view("superadmin.dashboard", compact("totalUsers", "totalDepartments", "totalCourses", "totalSchedules"));
    }
}
