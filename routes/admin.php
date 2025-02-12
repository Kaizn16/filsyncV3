<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AcademicController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;

Route::controller(DashboardController::class)->group(function() {
    Route::get('/Dashboard', 'index')->name('admin.dashboard');
});

Route::controller(UserController::class)->prefix('Users')->group(function() {
    Route::get('/','index')->name('admin.users');
    Route::get('/Fetch/Users','fetchUsers')->name('admin.fetch.users');
    Route::post('/Create/Store', 'store')->name('admin.store.user');
    Route::put('/Update', 'update')->name('admin.update.user');
    Route::delete('/Delete', 'delete')->name('admin.delete.user');
});

Route::controller(ScheduleController::class)->prefix('Schedules')->group(function() {
    Route::get('/','index')->name('admin.schedules');
    Route::get('Departments/Fetch','fetchDepartments')->name('admin.fetch.departments');
    Route::get('{department}','departmentCourses')->name('admin.department.courses');
    Route::get('{department}/{course}','courseSchedules')->name('admin.course.schedule');
    Route::post('{department}/{course}/Create-Schedule-Draft','create')->name('admin.schedule.draft.create');
    Route::put('{department}/{course}/Schedule-Draft/{schedule_draft_id}/Save', 'savedScheduleDraft')->name('admin.schedule.draft.saved');
    Route::delete('{department}/{course}/Create-Schedule-Draft/Cancel', 'cancelScheduleDraft')->name('admin.schedule.draft.cancel');
    Route::get('{department}/{course}/My-Schedule-Drafts','view')->name('admin.schedule.mydraft');
    Route::get('{department}/{course}/{schedule_draft_id}/Edit','edit')->name('admin.edit.schedule.draft');
    Route::post('Create/Store/Schedule', 'storeSchedule')->name('admin.schedule.store');
    Route::put('Update/Schedule', 'updateSchedule')->name('admin.schedule.update');
    Route::delete('Delete/Selected-Schedule', 'deleteSelectedSchedule')->name('admin.delete.schedule');
    Route::get('Course/Fetch/Subjects', 'fetchSubjects')->name('admin.fetch.subjects');
    Route::get('ScheduleDraft/Schedules/Fetch', 'fetchScheduleByDraft')->name('fetch.draftschedules');
    Route::delete('ScheduleDraft/My-Drafts/Delete', 'deleteMyDraft')->name('admin.delete.schedule.draft');
    Route::put('Schedule/My-Drafts/Deleted/Restore', 'restoreMyDraft')->name('admin.restore.schedule.draft');
    Route::get('ScheduleDraft/My-Drafts/Fetch', 'fetchMyScheduleDrafts')->name('admin.fetch.myschedule.drafts');
    Route::put('ScheduleDraft/My-Draft/Send/VPAA', 'sendScheduleDraftToVPAA')->name('admin.scheduledraft.send');
    Route::get('ScheduleDraft/My-Draft/Show', 'showSelectedScheduleDraft')->name('admin.scheduledraft.show');
});


Route::controller(RoomController::class)->prefix('Rooms')->group(function() {
    Route::get('/', 'index')->name('admin.rooms');
    Route::get('Rooms/Schedules/Fetch', 'fetchSchedules')->name('admin.fetch.rooms.schedule');
    Route::get('Rooms/Schedule/Edit', 'editSchedule')->name('admin.room.edit.schedule');
    Route::PUT('Rooms/Schedule/Update', 'updateSchedule')->name('admin.room.update.schedule');
});


Route::controller(SettingController::class)->prefix('Settings')->group(function() {
    Route::get('/','index')->name('admin.settings');
    Route::put('/Theme/Change', 'changeTheme')->name('admin.changeTheme');
});
