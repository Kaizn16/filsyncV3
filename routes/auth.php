<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticationController;

Route::controller(AuthenticationController::class)->group(function() {
    Route::get('/login', 'index')->name('login');
    Route::post('/check', 'store')->name('login.check');
    Route::post('/logout', 'logout')->name('auth.logout');
});