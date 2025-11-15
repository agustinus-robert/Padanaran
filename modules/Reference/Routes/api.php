<?php

// Search country states
Route::get('/country-states/search', 'CountryStateController@search')->name('country-states.search');

// Get phones
Route::get('/phones/index', 'PhoneController@index')->name('phones.index');

// Get grade
Route::get('/grade/index', 'GradeController@index')->name('grade.index');
