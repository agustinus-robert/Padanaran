<?php

Route::get('/company/inventory/categories', 'CategoryController@category')->name('company.inventory.categories');
Route::get('/company/inventory/brands', 'CategoryController@brand')->name('company.inventory.brands');
Route::get('/company/inventory/codes', 'CategoryController@code')->name('company.inventory.codes');
Route::get('/company/inventory/counts', 'CategoryController@count')->name('company.inventory.counts');
