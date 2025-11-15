<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Modules\Admin\Models\Post;
use Livewire\Livewire;
use Modules\Web\Http\Livewire\ProductsCommerces\ProductManage;
use  Modules\Editor\Models\EditorWebPages;

// routes/web.php
//Route::get('/', 'HomeCommerceController@index')->name('home.page');
// Route::get('/{page}', function ($page) {
//     $pageData = json_decode(EditorWebPages::where('page', $page)->first()->web_json, true)['content'];
//     return view('web::dynamic-page', compact('pageData'));
// });

Route::get('/about', 'AboutCommerceController@index')->name('about.page');
Route::get('/services', 'ServicesCommerceController@index')->name('services.page');
Route::get('/products', 'ProductCommerceController@index')->name('products.page');
Route::get('/cart', 'CartCommerceController@index')->name('cart.page');
Route::get('/deliver', 'DeliverCommerceController@index')->name('deliver.page');
Route::get('/payment/{uuid}', 'PaymentCommerceController@index')->name('payment.page');
Route::get('/contact', 'ContactCommerceController@index')->name('contact.page');
Route::post('/payment-transaction', 'PaymentCommerceController@status')->name('payment.transaction');
Route::get('/pbuilder', 'PBuilderController@index')->name('page.pbuilder');

Route::prefix('builder')->namespace('Builder')->name('builder.')->group(function () {
    Route::get('/builder', 'PageBuilderController@index')->name('page.builder');
});
