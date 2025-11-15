<?php

use Illuminate\Support\Facades\Route;
use Modules\Finance\Http\Middleware\AccessMiddleware;

Route::middleware(['auth', AccessMiddleware::class])->group(function () {

    // Redirect to dashboard
    Route::get('/', fn() => redirect()->route('finance::dashboard'))->name('index');

    // Dashboard
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

    // Payroll
    Route::prefix('payroll')->namespace('Payroll')->name('payroll.')->group(function () {

        // Validations
        Route::resource('validations', 'ValidationController')->parameters(['validations' => 'salary']);
        // Options
        Route::post('validation-options/notify-director', 'ValidationOptionController@notifyToDirector')->name('validation-options.notify-director');
        Route::post('validation-options/release', 'ValidationOptionController@release')->name('validation-options.release');
        Route::post('validation-options/notify-employees', 'ValidationOptionController@notifyToEmployees')->name('validation-options.notify-employees');

        // Components
        Route::get('templates/export', 'ExportTemplateController@index')->name('templates.export');
        Route::resource('templates', 'TemplateController');
        Route::resource('calculations', 'CalculationController')->parameters(['calculations' => 'salary']);

        // Tax issues
        Route::get('tax-issues/{salary}/show', 'PPhValidationController@show')->name('tax-issues.show');
        Route::put('tax-issues/{salary}/update', 'PPhValidationController@update')->name('tax-issues.update');

        // Approval
        // Route::resource('approvals', 'ApprovalController')->only('index', 'show', 'edit', 'update')->parameters(['approvals' => 'salary']);
    });

    // Services
    Route::prefix('service')->namespace('Service')->name('service.')->group(function () {

        Route::prefix('overtime')->namespace('Overtime')->name('overtime.')->group(function () {
            // Approvable
            Route::put('manage/{overtime}/approvable/{approvable}', 'ApprovableController@update')->name('manage.approvable.update');
            // Manage
            Route::put('manage/{overtime}/restore', 'ManageController@restore')->name('manage.restore');
            Route::resource('manage', 'ManageController')->parameters(['manage' => 'overtime'])->only('index', 'show', 'update', 'destroy');
        });

        Route::prefix('outwork')->namespace('Outwork')->name('outwork.')->group(function () {
            // Approvable
            Route::put('manage/{outwork}/approvable/{approvable}', 'ApprovableController@update')->name('manage.approvable.update');
            // Manage
            Route::put('manage/{outwork}/restore', 'ManageController@restore')->name('manage.restore');
            Route::resource('manage', 'ManageController')->parameters(['manage' => 'outwork']);
        });

        // Loan
        Route::namespace('Loan')->group(function () {
            // Loans
            Route::get('loans', 'LoanController@index')->name('loans.index');
            Route::get('loans/create', 'LoanController@create')->name('loans.create');
            Route::post('loans', 'LoanController@store')->name('loans.store');
            Route::get('loans/{loan}', 'LoanController@show')->name('loans.show');
            Route::put('loans/{loan}', 'LoanController@update')->name('loans.update');
            Route::patch('loans/{loan}/paid', 'LoanController@togglePaid')->name('loans.paid');
            Route::put('/loans/{loan}/restore', 'LoanController@restore')->name('loans.restore');
            Route::delete('loans/{loan}', 'LoanController@destroy')->name('loans.destroy');

            Route::get('loans/{loan}/print/', 'PrintController@index')->name('loans.print');

            // Loan transactions
            Route::get('loans/{loan}/transactions', 'LoanTransactionController@index')->name('loans.transactions.index');
            Route::get('loans/{loan}/transactions/create', 'LoanTransactionController@create')->name('loans.transactions.create');
            Route::post('loans/transactions/store', 'LoanTransactionController@store')->name('loans.transactions.store');

            // Loan payment list
            Route::get('/loan-transaction-lists/', 'LoanTransactionListController@index')->name('loan-transaction-lists.index');
            Route::put('/loan-transaction-lists/{transaction}/restore', 'LoanTransactionListController@restore')->name('loan-transaction-lists.restore');
            Route::delete('/loan-transaction-lists/{transaction}/destroy', 'LoanTransactionListController@restore')->name('loan-transaction-lists.destroy');
        });

        Route::prefix('deduction')->namespace('Deduction')->name('deduction.')->group(function () {
            Route::resource('manage', 'ManageController')->parameters(['manage' => 'deduction']);
        });
    });

    // Benefit
    Route::prefix('benefit')->namespace('Benefit')->name('benefit.')->group(function () {
        // Insurance
        Route::prefix('insurances')->namespace('Insurance')->name('insurances.')->group(function () {
            // Registration
            Route::post('registrations/max-salary', 'RegistrationController@savemaxsalary')->name('registrations.max-salary');
            Route::delete('registrations/{employee}/reset', 'RegistrationController@reset')->name('registrations.reset');
            Route::delete('registrations/{insurance}/destroy', 'RegistrationController@destroy')->name('registrations.destroy');
            Route::resource('registrations', 'RegistrationController')->parameters(['registrations' => 'employee'])->only('index', 'create', 'store');
        });
    });

    // Recapitulation
    Route::prefix('summary')->namespace('Summary')->name('summary.')->group(function () {
        // Outworks
        Route::resource('outworks', 'OutworkController')->only('index', 'create', 'store', 'destroy')->parameters(['outworks' => 'employee']);
        // Overtime
        Route::resource('overtimes', 'OvertimeController')->only('index', 'create', 'store', 'destroy')->parameters(['overtimes' => 'employee']);
        // Deduction
        Route::resource('deductions', 'DeductionController');
        //postyears
        Route::resource('postyear', 'PostYearController');
        //feastdays
        Route::resource('feastday', 'FeastDayController');

        Route::resource('teachings', 'TeacherController')->only('index', 'show', 'create', 'store', 'destroy', 'update')->parameters(['teaching' => 'employee']);
        Route::put('/submissionappr', 'TeacherController@submissionApprovals')->name('summary.permission');

        Route::resource('coords', 'CoordinatorController')->only('index', 'show', 'create', 'store', 'update', 'destroy')->parameters(['coords' => 'employee']);
        Route::put('/coordinatorappr/{recap}', 'CoordinatorController@submissionApprovals')->name('summary.coordinator.permission');
    });

    Route::prefix('report')->namespace('Report')->name('report.')->group(function () {
        // Salaries
        Route::resource('salaries', 'SalaryController')->only('index');
        Route::get('salaries/excel', 'SalaryController@excel')->name('salaries.excel');
        // Overtime
        Route::resource('overtimes', 'OvertimeController')->only('index');
        Route::get('overtimes/excel', 'OvertimeController@excel')->name('overtimes.excel');
    });

    Route::prefix('tax')->namespace('Tax')->name('tax.')->group(function () {
        // Template
        Route::resource('templates', 'TemplateController');
        // PPh 21
        Route::resource('employeetaxs', 'EmployeetaxController');
        // PTKP
        Route::resource('ptkps', 'PtkpController')->only('index', 'show', 'update', 'destroy');
        // Bukti potong
        Route::post('incomes/release', 'IncometaxController@release')->name('incomes.release');
        Route::resource('incomes', 'IncometaxController');
        // PPh calculation
        Route::post('income-taxs/release', 'PphController@release')->name('income-taxs.release');
        Route::get('income-taxs/summary', 'PphController@summary')->name('income-taxs.summary');
        Route::resource('income-taxs', 'PphController')->parameters(['income_taxs' => 'tax']);
    });
});
