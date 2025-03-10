<?php
use App\Http\Controllers\VPAA\DashboardController;
use App\Http\Controllers\VPAA\ScheduleController;
use App\Http\Controllers\VPAA\SettingController;
use Illuminate\Support\Facades\Route;

Route::controller(DashboardController::class)->prefix('Dashboard')->group(function () {
    Route::get('/', 'index')->name('vpaa.dashboard');
});

Route::controller(ScheduleController::class)->prefix('Schedules')->group(function () {
    Route::get('/', 'index')->name('vpaa.schedules');
    Route::get('Fetch/Schedules/Drafts', 'fetchSchedulesDraft')->name('vpaa.fetch.schedules.draft');
    Route::get('/View/{schedule_draft_id}', 'show')->name('vpaa.view.schedules');
    Route::get('Fetch/Schedules/Drafts/Subjects', 'fetchScheduleByDraft')->name('vpaa.fetch.schedule.draft.subjects');
    Route::put('Schedule/Draft/Update/Status/{schedule_draft_id}', 'updateScheduleDraftStatus')->name('vpaa.update.schedule.draft.status');
});

Route::controller(SettingController::class)->prefix('Settings')->group(function () {
    Route::get('/', 'index')->name('vpaa.settings');
    Route::put('/Theme/Change', 'changeTheme')->name('vpaa.changeTheme');
});