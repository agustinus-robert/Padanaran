<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {
    Route::get('/employee', 'EmployeeController@index')->name('employee.index');

    // Attendance
    Route::prefix('attendance')->namespace('Attendance')->name('attendance.')->group(function () {
        // Schedule
        Route::get('/schedules', 'ScheduleController@index')->name('schedules');
        Route::get('/schedules/today', 'ScheduleController@show')->name('schedules.today');
        // Presence
        Route::get('/presences', 'PresenceController@index')->name('presence');
        Route::post('/presences/store', 'PresenceController@store')->name('presence.store');
    });

    // vacations
    Route::prefix('vacation')->namespace('Vacation')->name('vacation.')->group(function () {
        // quota
        Route::get('/quotas', 'QuotaController@index')->name('quotas');
        // Presence
        Route::get('/submission', 'SubmissionController@index')->name('submission');
        Route::get('/submission/{vacation}/show', 'SubmissionController@show')->name('submission.show');
        Route::post('/submission/store', 'SubmissionController@store')->name('submission.store');
    });

    // leave
    Route::prefix('leave')->namespace('Leave')->name('leave.')->group(function () {
        // Presence
        Route::get('/submission', 'SubmissionController@index')->name('submission');
        Route::get('/submission/{leave}/show', 'SubmissionController@show')->name('submission.show');
        Route::post('/submission/store', 'SubmissionController@store')->name('submission.store');
    });
});
