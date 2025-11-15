<?php
use Modules\Admin\Http\Controllers\API\PostingBuildController;
use Modules\Admin\Http\Controllers\API\MenuBuildController;

use Illuminate\Support\Facades\Route;

Route::get('/classification', 'ClassificationController@search')->name('classification');

Route::get('/inventories', 'InventoriesController@search')->name('inventories');

Route::get('/employees/search', 'EmployeeController@search')->name('employees.search');

Route::resource('/build-post', PostingBuildController::class);
Route::resource('/build-menu', MenuBuildController::class);
