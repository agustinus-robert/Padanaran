<?php

use Illuminate\Support\Facades\Route;

Route::resource('user-token', 'UserApiController');

Route::middleware('api.key')->group(function () {
    Route::resource('product-api', 'ProductApiController');
    Route::resource('supplier-api', 'SupplierApiController');
    Route::resource('category-api', 'CategoryApiController');
    Route::resource('brand-api', 'BrandApiController');
    Route::resource('sale-api', 'SaleApiController');
    Route::resource('cart-api', 'CartApiController');
    Route::resource('outlet-api', 'OutletApiController');
    Route::resource('customer-desk-api', 'DeskCustomerApiController');
    Route::resource('cash-register-api', 'CashRegisterApiController');
    Route::resource('tax-api', 'TaxApiController');
    Route::get('adjustment-supplier-api/{supplier_id}', 'StockAdjustmentApiController@showSupplier');
    Route::resource('adjustment-api', 'StockAdjustmentApiController')->only('store', 'show');

    Route::get('tax-api-now', 'TaxApiController@cekPajak');
    Route::post('plus-cart', 'CartApiController@plus');
    Route::post('minus-cart', 'CartApiController@minus');
    Route::post('qtys-cart', 'CartApiController@updateQty');
    Route::delete('delete-cart', 'CartApiController@deleteAllCart');

    Route::put('transaction-add-item/{transaction_id}', 'SaleApiController@addItemDirect');
    Route::put('transaction-qty-item/{transaction_id}', 'SaleApiController@updateQtyItemDirect');
    Route::put('transaction-cust-desk/{transaction_id}', 'SaleApiController@deskCustDirects');
    Route::put('transaction-delete-item/{transaction_id}', 'SaleApiController@deleteItemDirect');
});
