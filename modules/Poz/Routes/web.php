<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Modules\Poz\Http\Middleware\IsPozMiddleware;
use Modules\Poz\Http\Middleware\IsSupplierMiddleware;

Route::middleware('auth', IsSupplierMiddleware::class)->group(function () {
    Route::get('/supplier/dashboard', 'SupplierPosDashboardController@index')->name('supplier.dashboard');

    Route::prefix('supplierz')->namespace('Supplierz')->name('supplierz.')->group(function () {
        Route::prefix('reporting')->namespace('Reporting')->name('reporting.')->group(function () {
            Route::resource('/supplier/product_supplier_reporting', 'SupplierShiftController')->parameters(['prodsupreportings' => 'prodsupreporting'])->only('index');
            Route::get('/supplier/datatable_supplier_product', 'SupplierShiftController@getStockProducts')->name('reporting.supplier.product.datatables');

            Route::resource('/supplier/product_reporting', 'ProductReportingController')->parameters(['productreportings' => 'productreporting'])->only('index');
            Route::get('/supplier/datatable_report_product', 'ProductReportingController@getReportProducts')->name('reporting.product.datatables');
        });

        Route::get('quotation-send/{quotation}', 'QuotationController@send')->name('quotation.send');
        Route::resource('quotation', 'QuotationController')->parameters(['quotations' => 'quotation']);
        //datatables
        Route::get('datatable_adjustment', 'AdjustmentController@adjustmentTable')->name('adjustment.datatables');
        Route::get('datatable_quotation', 'QuotationController@quotationTable')->name('quotation.datatables');

        //Transaction
        Route::resource('adjustment', 'AdjustmentController')
        ->only(['index', 'create', 'store'])->parameters(['adjustments' => 'adjustment']);
    });
});

Route::middleware('auth', 'append.outlet', IsPozMiddleware::class)->group(function () {
    //<!---------------------- Datatable ------------------------------>
    Route::prefix('transaction')->namespace('Transaction')->name('transaction.')->group(function () {
        Route::get('datatable_product', 'ProductController@productTable')->name('product.datatables');
        Route::get('datatable_sale', 'SaleController@saleTable')->name('sale.datatables');
        Route::get('purchaseTable', 'PurchaseController@purchaseTable')->name('purchase.datatables');
        Route::get('datatable_transfer', 'TransferController@transferTable')->name('transfer.datatables');
        Route::get('datatable_return', 'ReturnController@returnTable')->name('return.datatables');
        Route::get('datatable_adjustment', 'AdjustmentController@adjustmentTable')->name('adjustment.datatables');
        Route::get('datatable_quotation', 'QutationController@quotationTable')->name('quotation-transaction.datatables');
    });

    Route::prefix('master')->namespace('Master')->name('master.')->group(function () {
        Route::get('datatable_brand', 'BrandController@brandTable')->name('brand.datatables');
        Route::get('datatable_category', 'CategoryController@categoryTable')->name('category.datatables');
        Route::get('datatable_tax', 'TaxRateController@taxTable')->name('tax.datatables');
        Route::get('datatable_unit', 'UnitController@unitTable')->name('unit.datatables');
        Route::get('datatable_warehouse', 'WarehouseController@unitTable')->name('warehouse.datatables');
        Route::get('datatable_outlet', 'OutletController@outletTable')->name('outlet.datatables');
        Route::get('datatable_casier', 'CasierController@casierTable')->name('casier.datatables');
    });

    Route::prefix('reporting')->namespace('Reporting')->name('reporting.')->group(function () {
        Route::get('datatable_reporting_product', 'ProductReportingController@productReportTable')->name('reporting.product.datatables');
        Route::get('datatable_supplier_product', 'SupplierShiftController@getStockProducts')->name('reporting.supplier.product.datatables');
    });

    Route::prefix('schedule')->namespace('Schedule')->name('schedule.')->group(function(){
        Route::get('datatable_schedule_supplier', 'SupplierScheduleController@supplierScheduleTable')->name('schedule_supplier.datatables');
    });

    //<!---------------------- End Datatable ------------------------------>

    Route::get('/dashboard', 'DashboardPosController@index')->name('dashboard');
    Route::get('/processing', 'ProcessingPosAdminController@processing')->name('processing');

    Route::prefix('reporting')->namespace('Reporting')->name('reporting.')->group(function () {
        Route::resource('product_reporting', 'ProductReportingController')->parameters(['prodreportings' => 'prodreporting'])->only('index');
        Route::resource('product_supplier_reporting', 'SupplierShiftController')->parameters(['prodsupreportings' => 'prodsupreporting'])->only('index');
    });

    Route::prefix('master')->namespace('Master')->name('master.')->group(function () {
        Route::resource('brand', 'BrandController')->parameters(['brands' => 'brand']);
        Route::resource('category', 'CategoryController')->parameters(['categorys' => 'category']);
        Route::resource('tax', 'TaxRateController')->parameters(['taxs' => 'tax']);
        Route::resource('unit', 'UnitController')->parameters(['units' => 'unit']);
        Route::resource('warehouse', 'WarehouseController')->parameters(['warehouses' => 'warehouse']);
        Route::resource('outlet', 'OutletController')->parameters(['outlets' => 'outlet']);
        Route::resource('casier', 'CasierController')->parameters(['casiers' => 'casier']);
        Route::resource('supplier', 'SupplierController')->parameters(['suppliers' => 'supplier']);
    });

    Route::prefix('schedule')->namespace('Schedule')->name('schedule.')->group(function(){
        Route::resource('supplier_schedule', 'SupplierScheduleController')->parameters(['suppliers_schedules' => 'suppliers_schedules']);
    });

    Route::prefix('transaction')->namespace('Transaction')->name('transaction.')->group(function () {
        Route::resource('product', 'ProductController')->parameters(['products' => 'product']);
        Route::resource('sale', 'SaleController')->parameters(['sales' => 'sale']);
        Route::resource('qutation', 'QutationController')
            ->only(['index', 'show', 'update'])
            ->parameters(['qutations' => 'qutation']);        
            
        Route::resource('purchase', 'PurchaseController')->parameters(['purchases' => 'purchase']);
        Route::resource('return', 'ReturnController')->parameters(['returns' => 'return']);
        Route::resource('adjustment', 'AdjustmentController')->parameters(['adjustments' => 'adjustment']);
        Route::get('sale-pos-invoice/{sale_id}', 'SaleController@invoice')->name('sale.pos-invoice');
        Route::get('purchase_status/{purchase_id}/', 'PurchaseController@change_status')->name('purchase.purchase_status');
        Route::get('purchase-invoice/{purchase_id}', 'PurchaseController@invoice')->name('purchase.invoice');
        Route::resource('pos-sale', 'PosSaleController')->parameters(['pos-sales' => 'pos-sale']);
        Route::resource('transfer', 'TransferController')->parameters(['transfers' => 'transfer']);
        Route::get('transfer_status/{transfer_id}/', 'TransferController@change_status')->name('transfer.purchase_status');
        Route::get('transfer-invoice/{transfer_id}', 'TransferController@invoice')->name('transfer.invoice');
    });
});
