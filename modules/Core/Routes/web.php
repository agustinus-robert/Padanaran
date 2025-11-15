<?php

use Illuminate\Support\Facades\Route;
use Modules\Core\Http\Middleware\AccessMiddleware;

Route::middleware(['auth', AccessMiddleware::class])->group(function () {

    // Redirect to dashboard
    Route::get('/', fn() => redirect()->route('core::dashboard'))->name('index');

    // Dashboard
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

    Route::prefix('admin-extra')->namespace('AdminExtra')->name('admin-extra.')->group(function () {
        Route::get('/choose-education', 'EducationController@index')->name('choose.extra-education');
        Route::post('/choose-education', 'EducationController@store')->name('choose.extra-education.store');
    });
    // Company
    Route::prefix('company')->namespace('Company')->name('company.')->group(function () {
        // Role references
        Route::put('/roles/{role}/restore', 'RoleController@restore')->name('roles.restore');
        Route::put('/roles/{role}/permissions', 'RoleController@permissions')->name('roles.permissions');
        Route::resource('roles', 'RoleController')->except('edit', 'create');

        // Department references
        Route::put('/departments/{department}/restore', 'DepartmentController@restore')->name('departments.restore');
        Route::resource('departments', 'DepartmentController')->except('edit');

        // Position references
        Route::put('/positions/{position}/restore', 'PositionController@restore')->name('positions.restore');
        Route::resource('positions', 'PositionController')->except('edit');

        // Services
        Route::prefix('services')->namespace('Service')->name('services.')->group(function () {
            // Leave categories
            Route::put('/leave-categories/{category}/restore', 'LeaveCategoryController@restore')->name('leave-categories.restore');
            Route::resource('leave-categories', 'LeaveCategoryController')->parameters(['leave-categories' => 'category'])->except('edit');

            Route::put('/leave-student-categories/{category}/restore', 'LeaveStudentCategoryController@restore')->name('leave-student-categories.restore');
            Route::resource('leave-student-categories', 'LeaveStudentCategoryController')->parameters(['leave-student-categories' => 'category'])->except('edit');

            // Vacation categories
            Route::put('/vacation-categories/{category}/restore', 'VacationCategoryController@restore')->name('vacation-categories.restore');
            Route::resource('vacation-categories', 'VacationCategoryController')->parameters(['vacation-categories' => 'category'])->except('edit');

            // outwork categories
            Route::put('/outwork-categories/{category}/restore', 'OutworkCategoryController@restore')->name('outwork-categories.restore');
            Route::resource('outwork-categories', 'OutworkCategoryController')->parameters(['outwork-categories' => 'category'])->except('edit');

            // outwork categories
            Route::put('/loan-categories/{category}/restore', 'LoanCategoryController@restore')->name('loan-categories.restore');
            Route::resource('loan-categories', 'LoanCategoryController')->parameters(['loan-categories' => 'category'])->except('edit');
        });

        // Moments
        Route::get('moments/sync', 'MomentController@sync')->name('moments.sync');
        Route::resource('moments', 'MomentController')->except('edit');

        // Insurances
        Route::prefix('insurances')->namespace('Insurance')->name('insurances.')->group(function () {
            // Manage insurance
            Route::resource('manages', 'ManageController')->parameters(['manages' => 'insurance']);
        });

        // Salaries
        Route::prefix('salaries')->namespace('Salary')->name('salaries.')->group(function () {
            // Salary slips
            Route::put('/slips/{slip}/restore', 'SlipController@restore')->name('slips.restore');
            Route::resource('slips', 'SlipController')->parameters(['slips' => 'slip'])->except('create', 'edit');

            // Salary categories
            Route::put('/categories/{category}/restore', 'CategoryController@restore')->name('categories.restore');
            Route::resource('categories', 'CategoryController')->parameters(['categories' => 'category'])->except('edit');

            // Salary Components
            Route::put('/components/{component}/restore', 'ComponentController@restore')->name('components.restore');
            Route::resource('components', 'ComponentController')->except('edit');

            // Salary Slip Template
            Route::put('/templates/{template}/restore', 'TemplateController@restore')->name('templates.restore');
            Route::get('/templates/sync', 'TemplateController@sync')->name('templates.sync');
            Route::resource('templates', 'TemplateController')->except('edit');

            // Salary Slip configs
            Route::resource('configs', 'ConfigController');
        });

        Route::prefix('assets')->namespace('Asset')->name('assets.')->group(function () {
            // Buildings
            Route::put('/buildings/{building}/restore', 'BuildingController@restore')->name('buildings.restore');
            Route::resource('buildings', 'BuildingController')->parameters(['buildings' => 'building'])->except('edit');

            // Rooms
            Route::put('/rooms/{room}/restore', 'RoomController@restore')->name('rooms.restore');
            Route::resource('rooms', 'RoomController')->parameters(['rooms' => 'room']);
        });
    });

    // System
    Route::prefix('system')->namespace('System')->name('system.')->group(function () {
        // Users
        Route::put('/users/{user}/restore', 'UserController@restore')->name('users.restore');
        Route::put('/users/{user}/repass', 'UserController@repass')->name('users.repass');
        Route::put('/users/{user}/profile', 'User\ProfileController@update')->name('users.update.profile');
        Route::put('/users/{user}/username', 'User\UsernameController@update')->name('users.update.username');
        Route::put('/users/{user}/email', 'User\EmailController@update')->name('users.update.email');
        Route::put('/users/{user}/role', 'User\RoleController@update')->name('users.update.role');
        Route::put('/users/{user}/phone', 'User\PhoneController@update')->name('users.update.phone');
        Route::post('/users/{user}/login', 'UserController@login')->name('users.login');
        Route::resource('users', 'UserController')->except(['create', 'edit', 'update']);
        // User logs
        Route::resource('user-logs', 'UserLogController')->parameters(['user-logs' => 'log'])->only('index', 'destroy');
        // Roles
        // Route::get('settings', 'SettingController@index')->name('settings.index');
        // Route::put('settings/{setting}', 'SettingController@update')->name('settings.update');
    });

    Route::get('phpmyinfo', function () {
        phpinfo();
    })->name('phpmyinfo');
});
