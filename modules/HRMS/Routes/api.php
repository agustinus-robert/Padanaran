<?php

use Illuminate\Support\Facades\Route;
use Modules\HRMS\Http\Controllers\API\TeacherReportController;
use Modules\HRMS\Http\Controllers\API\TeacherDetailController;

// use Modules\HRMS\Http\Controllers\API\Service\Vacation\ApprovableControllerAPI as ApprovableVacation;
// use Modules\HRMS\Http\Controllers\API\Service\Vacation\ManageControllerAPI as ManageVacation;

// use Modules\HRMS\Http\Controllers\API\Service\Leave\ApprovableControllerAPI as ApprovableLeave;
// use Modules\HRMS\Http\Controllers\API\Service\Leave\ManageControllerAPI as ManageLeave;



// Search active employees
Route::get('/employees/search', 'EmployeeController@search')->name('employees.search');

Route::middleware('guest:api')->group(function () {
    Route::get('/positions/all', 'PositionController@all')->name('positions.all');
    Route::get('/attendance-report/', 'AttendaceReportController@index')->name('attendance-report');
});

Route::middleware('auth:api')->group(function () {
    Route::get('/employee', 'EmployeeController@index')->name('employee.index');
});

Route::middleware('auth:sanctum')->group(function() {
    Route::get('/teacher-report-list', [TeacherReportController::class, 'index'])->name('teacher-report.list');
    Route::get('/classroom-list', [TeacherDetailController::class, 'getTeacherClassroom'])->name('classroom-list');
    Route::get('/lesson-list', [TeacherDetailController::class, 'getLessonByClassroom'])->name('lesson-list');
    Route::get('/teaching-data-list', [TeacherDetailController::class, 'getTodayLessonsWithClassroom'])->name('teaching-data-list');
});

Route::prefix('service')->namespace('Service')->name('service.')->middleware('auth:sanctum')->group(function() {
    Route::prefix('vacation')->namespace('Vacation')->name('vacation.')->group(function(){
        Route::put('manage/{vacation}/approvable/{approvable}', 'ApprovableControllerAPI@update')->name('vacation.approvable.update');
        Route::apiResource('vacations', 'ManageControllerAPI');
    });

    Route::prefix('leave')->namespace('Leave')->name('leave.')->group(function() {
        Route::put('manage/{leave}/approvable/{approvable}', 'ApprovableControllerAPI@update')->name('leave.approvable.update');
        Route::apiResource('leaves', 'ManageControllerAPI');
    });
});

Route::prefix('attendance')->namespace('Attendance')->name('attendance.')->middleware('auth:sanctum')->group(function(){
    Route::get('/presence', 'PresenceControllerAPI@index')->name('presence.index');
    Route::post('/presence', 'PresenceControllerAPI@store')->name('presence.store');
});



;
