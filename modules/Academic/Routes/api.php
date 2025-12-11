<?php

use Modules\Academic\Http\Controllers\API\Meet\ScheduleControllerAPI;
use Modules\Academic\Http\Controllers\API\Meet\SubjectControllerAPI;
use Modules\Academic\Http\Controllers\API\Invoice\InvoicesControllerAPI;
use Modules\Academic\Http\Controllers\API\StudentPackageControllerAPI;

Route::middleware('auth:sanctum')->group(function() {
    Route::get('/package-student-list', [StudentPackageControllerAPI::class, 'index'])->name('package-student.list');
});

Route::prefix('meet')->middleware('auth:sanctum')->group(function() {
    Route::get('/meet-subject-list', [SubjectControllerAPI::class, 'index'])->name('meet.subject.list');
    Route::get('/meet-schedule-list', [ScheduleControllerAPI::class, 'index'])->name('meet.schedule.list');
});

Route::prefix('invoice')->middleware('auth:sanctum')->group(function() {
    Route::get('/invoice-student-list', [InvoicesControllerAPI::class, 'index'])->name('invoice.student.list');
});
;
