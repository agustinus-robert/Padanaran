<?php

use Modules\Account\Http\Controllers\API\CredentialController;
// Ensure to authentication first
Route::middleware('auth:api')->group(function () {

	// Get current user information based authorization token header
	Route::get('/user', 'UserController@me')->name('index');

	// Notifications prefix
	Route::prefix('notifications')->name('notifications.')->group(function () {

		// Get all notifications
		// Route::get('/', 'UserNotificationController@index')->name('index');
		// Mark notification
		// Route::post('/{notification}/read', 'UserNotificationController@read')->name('read');
		// Route::post('/{notification}/unread', 'UserNotificationController@unread')->name('unread');
		// Route::post('/read-all', 'UserNotificationController@readAll')->name('read');

	});
});

// Search active users
Route::get('/users/search', 'UserController@search')->name('users.search');


Route::post('/login-api', [CredentialController::class, 'login'])->name('login-api');