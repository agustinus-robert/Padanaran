<?php

use Illuminate\Support\Facades\Route;
use Modules\HRMS\Http\Controllers\API\TeacherReportController;
use Modules\HRMS\Http\Controllers\API\TeacherDetailController;

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
