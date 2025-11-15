<?php

use Illuminate\Support\Facades\Route;

// Redirect to verification
Route::get('/verification/{qr?}', 'VerificationController@index')->name('verify');

Route::middleware('auth')->group(function () {

    // Redirect to dashboard
    Route::get('/', fn () => redirect()->route('docs::home'))->name('home');

    // Dashboard    
    Route::get('/home', 'HomeController@index')->name('home');
    // Show document
    Route::get('/home/{document}', 'HomeController@show')->name('home.show');

    // Show by categories/type
    Route::get('/categories', 'CategoryController@index')->name('categories.index');
    Route::get('/categories/{type}', 'CategoryController@show')->name('categories.show');

    // Administration
    Route::prefix('manage')->namespace('Manage')->name('manage.')->group(function () {
        // Documents
        Route::get('/documents/{document}/download', 'DocumentController@download')->name('documents.download');
        Route::resource('/documents', 'DocumentController');
    });
});
