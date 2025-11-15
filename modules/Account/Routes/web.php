<?php

// Redirect

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('account::home'))->name('index');

// Auth page
Route::prefix('member')->namespace('Member')->name('member.')->group(function () {
	Route::resource('/registration', 'RegistrationAppController')->parameters(['registrations' => 'registration']);
});

Route::middleware('auth')->group(function () {

	// Home
	Route::view('/home', 'account::home')->name('home');
	Route::view('/home-member', 'account::home-member')->name('home-member');

	// User page
	Route::name('user.')->namespace('User')->group(function () {
		// Account
		Route::view('/profile', 'account::user.profile')->name('profile');
		Route::put('/profile', 'ProfileController@update')->name('profile');

		// Avatar
		Route::view('/avatar', 'account::user.avatar')->name('avatar');
		Route::put('/avatar', 'AvatarController@update')->name('avatar');
		Route::delete('/avatar', 'AvatarController@destroy')->name('avatar');

		// Email
		Route::view('/email', 'account::user.email')->name('email');
		Route::put('/email', 'EmailController@update')->name('email');
		Route::get('/email/reverify', 'EmailController@reverify')->name('email.reverify');

		Route::view('/phone', 'account::user.phone')->name('phone');
		Route::put('/phone', 'PhoneController@update')->name('phone');
		Route::delete('/phone', 'PhoneController@destroy')->name('phone');

		// Skype
		// Route::view('/skype', 'account::user.skype')->name('skype');
		// Route::put('/skype', 'SkypeController@update')->name('skype');
		// Route::delete('/skype', 'SkypeController@destroy')->name('skype');

		// Address
		// Route::view('/address', 'account::user.address')->name('address');
		// Route::put('/address', 'AddressController@update')->name('address');
		// Route::delete('/address', 'AddressController@destroy')->name('address');

		// Career
		// Route::resource('/careers', 'CareerController')->except('index')->parameters(['careers' => 'index']);

		// Education
		// Route::resource('/educations', 'EducationController')->except('index')->parameters(['educations' => 'index']);
		// Route::view('/educations', 'account::user.education')->name('educations');
		// Route::put('/educations', 'EducationController@update')->name('educations');
		// Route::delete('/educations', 'EducationController@destroy')->name('educations');

		// Route::middleware('password.confirm')->group(function () {

		// 	// Username
		// 	Route::view('/username', 'account::user.username')->name('username');
		// 	Route::put('/username', 'UsernameController@update')->name('username');
		// });


		// Password
		Route::view('/password', 'account::user.password')->name('password');
		Route::put('/password', 'PasswordController@update')->name('password');
		Route::post('/password/reset', 'PasswordController@reset')->name('password.reset');
	});

	// Notifications
	Route::view('/notifications', 'account::notifications')->name('notifications');
	Route::get('/notifications/read-all', 'NotificationController@readAll')->name('notifications.read-all');
	Route::get('/notifications/{id}', 'NotificationController@read')->name('notifications.read');
});

// Verifying email, without authentication middleware
Route::get('/email/verify', 'User\EmailController@verify')->name('user.email.verify');
