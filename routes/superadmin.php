<?php

use App\Http\Controllers\Superadmin\AcademicController;
use App\Http\Controllers\Superadmin\Dashboard;
use App\Http\Controllers\Superadmin\RoomController;
use App\Http\Controllers\Superadmin\ScheduleController;
use App\Http\Controllers\Superadmin\SettingController;
use App\Http\Controllers\Superadmin\UserController;
use App\Http\Middleware\IsSuperadmin;
use Illuminate\Support\Facades\Route;


Route::controller(Dashboard::class)->group(function() {
    Route::get('/Dashboard', 'index')->name('superadmin.dashboard');
});

Route::controller(UserController::class)->group(function() {
   Route::get('/Users', 'index')->name('superadmin.users');
   Route::get('/Users/List','fetchUsers')->name('superadmin.fetch.users');
   Route::post('/Users/Create/Store', 'store')->name('superadmin.store.user');
   Route::put('/User/Update', 'update')->name('superadmin.update.user');
   Route::delete('/User/Delete', 'delete')->name('superadmin.delete.user');
});

Route::controller(AcademicController::class)->group(function() {
    Route::get('/Academics','index')->name('superadmin.academics');
    Route::get('/Academics/Subjects','subjects')->name('superadmin.academics.subjects');
    Route::get('/Academics/Subjects/List','fetchSubjects')->name('superadmin.fetch.subjects');
    Route::post('/Academics/Subject/Create/Store','store')->name('superadmin.store.subject');
    Route::put('/Academics/Subject/Update','update')->name('superadmin.update.subject');
    Route::delete('/Acaddemics/Subjects/Selected/Subject/BulkDelete', 'bulkDelete')->name('superadmin.bulkdelete.subject');
});

Route::controller(RoomController::class)->group(function() {
    Route::get('/Rooms','index')->name('superadmin.rooms');
    Route::get('/Rooms/List','fetchRooms')->name('superadmin.fetch.rooms');
    Route::post('/Rooms/Create/Store','store')->name('superadmin.store.room');
    Route::put('/Room/Update','update')->name('superadmin.update.room');
    Route::delete('/Room/Delete','delete')->name('superadmin.delete.room');
});

Route::controller(SettingController::class)->group(function() {
    Route::get('/Settings','index')->name('superadmin.settings');
    Route::put('/Settings/Theme/Change', 'changeTheme')->name('superadmin.changeTheme');
});