<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Superadmin\ScheduleController;

Route::get('/', function () {
    return view('auth.login');
});



Route::get('/Fetch/Departments', [DepartmentController::class, 'fetchDepartments'])
    ->name('fetch.departments')
    ->middleware('auth');

Route::get('/Fetch/Department/Courses', [DepartmentController::class, 'fetchCoursesByDepartment'])
    ->name('fetch.courses.department')
    ->middleware('auth');

Route::get('/Fetch/Department/Courses/Course_No', [DepartmentController::class, 'fetchCourseNo'])
    ->name('fetch.courses.no')
    ->middleware('auth');

Route::get('/Fetch/Department/Courses/Course_No/Details', [DepartmentController::class, 'fetchCourseNoDetails'])
    ->name('fetch.courseno.details')
    ->middleware('auth');


Route::get('Fetch/Subjects', [ScheduleController::class, 'fetchSubjectsByCourse'])
    ->name('fetch.subjects')
    ->middleware('auth');