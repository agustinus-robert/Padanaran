<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

if(env('BUG') == 0){
	Route::prefix('builder')->namespace('Builder')->name('builder.')->group(function () {
		Route::get('/datatable', 'DataTableBuilderController@index')->name('datatable');
		Route::get('customs/datatable', 'CustomsController@getTable')->name('customs.datatables');
	});

	Route::prefix('configure')->namespace('Configure')->name('configure.')->group(function () {
		Route::get('/datatable', 'DataTableConfigureController@index')->name('datatable');
	});

	Route::prefix('custom')->namespace('CustomFeature')->name('custom.')->group(function () {
		Route::get('/datatable', 'DataTableCustomController@index')->name('datatable');
		Route::get('/datatable-donation', 'DonationDataController@getTable')->name('custom.datatable-donation');
	});
}

Route::middleware('auth')->group(function () {
	Route::get('/dashboard-cms', 'DashboardCmsController@index')->name('dashboard-cms');

	Route::prefix('builder')->namespace('Builder')->name('builder.')->group(function () {
		Route::resource('posting', 'PostingController')->parameters(['postings' => 'posting']);
		Route::resource('custom', 'CustomsController')->parameters(['customs' => 'custom']);
		Route::resource('account', 'AccountsController')->parameters(['accounts' => 'account']);
		// custom posting method
			Route::resource('posting_form', 'PostingFormController')->parameters(['posting_forms' => 'posting_form']);
			Route::resource('posting_form_list', 'PostingFormListController')->parameters(['posting_form_lists' => 'posting_form_list']);

			Route::get('/posting/{posting}/publish', 'PostingController@publish')->name('publish');
			Route::get('/posting/{posting}/draft', 'PostingController@draft')->name('draft');

			Route::get('/posting/{posting}/schedule_view', 'PostingController@sch_date')->name('view_schedule');
    		Route::post('/posting/{posting}/schedule_post', 'PostingController@post_sch')->name('post_schedule');
    	    Route::post('/posting/{posting}/schedule_cancel', 'PostingController@cancel_post_sch')->name('cancel_schedule');
		// custom posting method
		Route::resource('posting_image', 'PostingImageController')->parameters(['postings_images' => 'posting_images']);
		Route::resource('posting_video', 'PostingVideoController')->parameters(['postings_video' => 'posting_video']);
		Route::resource('menu', 'MenuController')->parameters(['menus' => 'menu']);
		Route::resource('order', 'OrderController')->parameters(['orders' => 'order']);
		Route::resource('category', 'CategoryzationController')->parameters(['categorys' => 'category']);
		Route::resource('role', 'RoleController')->parameters(['roles' => 'role']);
	});

	Route::prefix('configure')->namespace('Configure')->name('configure.')->group(function () {
		Route::resource('categoryzation_name', 'CategoryzationNameController')->parameters(['categoryzation_names' => 'categoryzation_name']);
		Route::resource('tags', 'TagsController')->parameters(['tags' => 'tag']);
	});

	Route::prefix('custom')->namespace('CustomFeature')->name('custom.')->group(function () {
		Route::resource('donation_data', 'DonationDataController')->parameters(['donations' => 'donation']);
		Route::resource('contact', 'ContactController')->parameters(['contacts' => 'contact']);
		Route::resource('career_data', 'CareerController')->parameters(['careers' => 'career']);
		Route::resource('contact_organization', 'ContactOrgController')->parameters(['contact-orgs' => 'contact-org']);
		Route::resource('news_subcribe', 'NewSubController')->parameters(['newsubs' => 'newsub']);
		Route::resource('site_configuration', 'SiteConfigController')->parameters(['siteconfigs' => 'siteconfig']);
		Route::resource('partnerships_data', 'PartnershipController')->parameters(['partnerships_datas' => 'partnerships_data', 'status_partnerships' => 'status_partnership']);
		Route::get('/partnership_id/{id}/status/{status}', 'PartnershipController@approve')->name('partnerships');
	});
});
