<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\PortalMiddleware;

Route::middleware(['auth', \Modules\Portal\Http\Middleware\ShopMiddleware::class])->group(function () {
    Route::resource('dashboard', 'HomeController');
    
    Route::prefix('outlet')->namespace('Outlet')->name('outlet.')->group(function () {
        Route::resource('manage-outlet', 'ManageController')->parameters(['manages' => 'manage']);
        Route::get('datatable_outlet', 'ManageController@outletTable')->name('outlet.datatables');
    });

});


Route::middleware(['auth', \Modules\Portal\Http\Middleware\AccessMiddleware::class])->group(function () {
    // Route::prefix('portal')->namespace('Portal')->name('portal.')->group(function () {
    //hijau
    Route::resource('dashboard-msdm', 'HomeMsdmController');
  
    Route::prefix('schedule-teacher')->namespace('ScheduleTeacher')->name('schedule-teacher.')->group(function () {
        Route::get('workshifts', 'ScheduleController@index')->name('workshifts');
        Route::get('manages/collective', 'CollectiveController@create')->name('manages.collective');
        Route::post('manages/collective', 'CollectiveController@store')->name('manages.collective.store');
        Route::resource('manages', 'ManageController')->parameters(['manages' => 'schedule']);
        Route::resource('submission', 'ManageSubmissionScheduleController')->parameters(['submissions' => 'submission']);
        Route::put('/submissionappr/{approvable}', 'ManageSubmissionScheduleController@SubmissionUpdate')->name('submissionappr.update');
        Route::get('submission/{submission}', 'ManageSubmissionScheduleController@show')
            ->name('submission.show');
    });

    Route::prefix('package')->namespace('Package')->name('package.')->group(function(){
        Route::resource('manage', 'ManageController');
    });

    Route::prefix('attendance')->namespace('Attendance')->name('attendance.')->group(function () {
        // Presence
        Route::redirect('/', '/attendance/presence');
        Route::get('/presence', 'PresenceController@index')->name('presence.index');
        Route::post('/presence', 'PresenceController@store')->name('presence.store');
    });
    
    Route::prefix('vacation')->namespace('Vacation')->name('vacation.')->group(function () {
        // Submission
        Route::get('/', 'SubmissionController@index')->name('submission.index');
        Route::get('/submission', 'SubmissionController@create')->name('submission.create');
        Route::post('/submission', 'SubmissionController@store')->name('submission.store');
        Route::get('/submission/{vacation}', 'SubmissionController@show')->name('submission.show');
        Route::get('/submission/{vacation}/edit', 'SubmissionController@edit')->name('submission.edit');
        Route::put('/submission/{vacation}', 'SubmissionController@update')->name('submission.update');
        Route::delete('/submission/{vacation}', 'SubmissionController@destroy')->name('submission.destroy');
        // Cancellation
        Route::get('/cancelation/{vacation}', 'CancelationController@show')->name('cancelation.show');
        Route::put('/cancelation/{vacation}', 'CancelationController@update')->name('cancelation.update');
        // Manage vacation submissions
        Route::get('/manage', 'ManageController@index')->name('manage.index');
        Route::get('/manage/{vacation}', 'ManageController@show')->name('manage.show');
        Route::put('/manage/{approvable}', 'ManageController@update')->name('manage.update');
        // Cashable
        Route::prefix('cashable')->namespace('Cashable')->name('cashable.')->group(function () {
            Route::get('/', 'CashableController@create')->name('create');
            Route::post('/', 'CashableController@store')->name('store');
            Route::get('/manage', 'ManageController@index')->name('manage.index');
            Route::get('/manage/{vacation}', 'ManageController@show')->name('manage.show');
            Route::put('/manage/{approvable}', 'ManageController@update')->name('manage.update');
        });
        // Quotas
        Route::get('/quotas', 'QuotaController@index')->name('quotas.index');
        Route::get('/quotas/excel', 'Quota\ExcelController@index')->name('quotas.excel.index');
        // Print
        Route::get('/print/{vacation}', 'PrintController@index')->name('print');
    });

    Route::prefix('leave')->namespace('Leave')->name('leave.')->group(function () {
        // Submission
        Route::get('/', 'SubmissionController@index')->name('submission.index');
        Route::get('/submission', 'SubmissionController@create')->name('submission.create');
        Route::post('/submission', 'SubmissionController@store')->name('submission.store');
        Route::get('/submission/{leave}', 'SubmissionController@show')->name('submission.show');
        Route::delete('/submission/{leave}', 'SubmissionController@destroy')->name('submission.destroy');
        // Manage leave submissions
        Route::get('/manage', 'ManageController@index')->name('manage.index');
        Route::get('/manage/{leave}', 'ManageController@show')->name('manage.show');
        Route::put('/manage/{approvable}', 'ManageController@update')->name('manage.update');
        // Print
        Route::get('/print/{leave}', 'PrintController@index')->name('print');
    });

    Route::prefix('overtime')->namespace('Overtime')->name('overtime.')->group(function () {
        // Submission
        Route::get('/', 'SubmissionController@index')->name('submission.index');
        Route::get('/submission', 'SubmissionController@create')->name('submission.create');
        Route::post('/submission', 'SubmissionController@store')->name('submission.store');
        Route::get('/submission/{overtime}', 'SubmissionController@show')->name('submission.show');
        Route::put('/submission/{overtime}/update', 'SubmissionController@update')->name('submission.update');
        Route::put('/submission/{overtime}/approve', 'SubmissionController@approve')->name('submission.approve');
        Route::delete('/submission/{overtime}', 'SubmissionController@destroy')->name('submission.destroy');
        // Ekspor
        Route::get('/export', 'ExportController@index')->name('export.index');
        // Manage overtime submissions
        Route::get('/manage', 'ManageController@index')->name('manage.index');
        Route::get('/manage/{overtime}', 'ManageController@show')->name('manage.show');
        Route::put('/manage/{approvable}', 'ManageController@update')->name('manage.update');
        Route::delete('/manage/{overtime}', 'ManageController@destroy')->name('manage.destroy');
        // Scheduled overtime
        Route::get('/manage/schedule/create', 'ScheduleController@create')->name('manage.schedule.create');
        Route::get('/manage/schedule/edit', 'ScheduleController@edit')->name('manage.schedule.edit');
        Route::post('/manage/schedule/store', 'ScheduleController@store')->name('manage.schedule.store');
        Route::put('/manage/schedule/update', 'ScheduleController@update')->name('manage.schedule.update');
    });

    // Outwork
    Route::prefix('outwork')->namespace('Outwork')->name('outwork.')->group(function () {
        // Submission
        Route::get('/', 'SubmissionController@index')->name('submission.index');
        Route::get('/submission', 'SubmissionController@create')->name('submission.create');
        Route::post('/submission', 'SubmissionController@store')->name('submission.store');
        Route::get('/submission/{outwork}', 'SubmissionController@show')->name('submission.show');
        Route::delete('/submission/{outwork}', 'SubmissionController@destroy')->name('submission.destroy');
        // Manage outwork submissions
        Route::get('/manage', 'ManageController@index')->name('manage.index');
        Route::get('/manage/{outwork}', 'ManageController@show')->name('manage.show');
        Route::put('/manage/{approvable}', 'ManageController@update')->name('manage.update');
    });

    Route::prefix('salary')->namespace('Salary')->name('salary.')->group(function () {
        // Slips
        Route::get('/slips/', 'SlipController@index')->name('slips.index');
        Route::get('/slips/{salary}/print', 'SlipController@show')->name('slips.show');
        Route::get('/slips/{salary}', 'SlipController@edit')->name('slips.edit');
        Route::put('/slips/{salary}', 'SlipController@update')->name('slips.update');
    });

    // Route::prefix('attendance')->namespace('Attendance')->name('attendance.')->group(function () {
    //     Route::redirect('/', '/attendance/presence');
    //     Route::get('/presence', 'PresenceController@index')->name('presence.index');
    //     Route::post('/presence', 'PresenceController@store')->name('presence.store');
    // });
    //});
    // Route::resource('donation', 'PaymentController')->parameters(['payments' => 'payment']);
    // Route::resource('donate', 'DonationController')->parameters(['payments' => 'payment']);
    // Route::resource('volunteer', 'VolunteerController')->parameters(['payments' => 'payment']);
    // Route::resource('history_partnership', 'PartnershipHistoryController')->parameters(['history_partnerships' => 'history_partnership']);
    // Route::resource('account', 'AccountSettingsController')->parameters(['accounts' => 'account']);
    // Route::get('history_volunteer', 'VolunteerController@volunteer_list')->name('history_volunteer');
});
