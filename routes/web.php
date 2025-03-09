<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Student\StudentController;

Route::get('/', function () {
    return view('auth.login');
});

Route::controller(StudentController::class)->prefix('Student')->group( function() {
    Route::get('Departments', 'index')->name('students');
    Route::get('Departments/{department_abbreviation}', 'schedules')->name('student.schedules');
    Route::get('Departments/Fetch/Schedules', 'fetchSchedules')->name('fetch.approved.schedules');
});


//APIS
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