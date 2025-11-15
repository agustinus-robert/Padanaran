<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

if(env('BUG') == 0){
	Route::prefix('inventories')->namespace('Inventory')->name('inventories.')->group(function () {
		Route::get('/datatable', 'DataTableController@index')->name('datatable');
	});
}

Route::middleware('auth')->group(function () {

	// Redirect to dashboard
	Route::get('/', fn () => redirect()->route('admin::index'))->name('index');

	Route::get('/dashboard-embark', 'DashboardEmbarkController@index')->name('dashboard-embark');

	// System
	Route::prefix('system')->namespace('System')->name('system.')->group(function () {
		// Roles
		Route::put('/roles/{role}/restore', 'RoleController@restore')->name('roles.restore');
		Route::put('/roles/{role}/permissions', 'RoleController@permissions')->name('roles.permissions');
		Route::resource('roles', 'RoleController')->except('edit', 'create');

		// Contract references
		Route::put('/contracts/{contract}/restore', 'ContractController@restore')->name('contracts.restore');
		Route::resource('contracts', 'ContractController')->except('edit');

		// Department references
		Route::put('/departments/{department}/restore', 'DepartmentController@restore')->name('departments.restore');
		Route::resource('departments', 'DepartmentController')->except('edit');

		// Position references
		Route::put('/positions/{position}/restore', 'PositionController@restore')->name('positions.restore');
		Route::resource('positions', 'PositionController')->except('edit');

		// User
		Route::put('users/{user}/profile', 'User\ProfileController@update')->name('users.update.profile');
		Route::put('users/{user}/username', 'User\UsernameController@update')->name('users.update.username');
		Route::put('users/{user}/email', 'User\EmailController@update')->name('users.update.email');
		Route::put('users/{user}/role', 'User\RoleController@update')->name('users.update.role');
		Route::put('users/{user}/phone', 'User\PhoneController@update')->name('users.update.phone');
		Route::put('users/{user}/restore', 'UserController@restore')->name('users.restore');
		Route::put('users/{user}/repass', 'UserController@repass')->name('users.repass');
		Route::post('users/{user}/login', 'UserController@login')->name('users.login');
		Route::resource('users', 'UserController')->except(['create', 'edit', 'update']);

		// Employee
	//	Route::resource('employees', 'EmployeeController')->except(['create', 'edit', 'update']);

		// User logs
		Route::resource('user-logs', 'UserLogController')->parameters(['user-logs' => 'log'])->only('index', 'destroy');

		// System configuration
		Route::get('phpmyinfo', function () {
			phpinfo();
		})->name('phpmyinfo');
	});

	// Employment
	Route::prefix('employment')->namespace('Employment')->name('employment.')->group(function () {
		// Employees
		Route::put('/employees/{employee}/restore', 'EmployeeController@restore')->name('employees.restore');
		Route::resource('employees', 'EmployeeController')->except('edit');

		// Contracts
		Route::get('contracts/{contract}/positions/create', 'PositionController@create')->name('contracts.positions.create');
		Route::post('contracts/{contract}/positions', 'PositionController@store')->name('contracts.positions.store');
		Route::get('contract-positions/{position}/edit', 'PositionController@edit')->name('contract-positions.edit');
		Route::put('contract-positions/{position}', 'PositionController@update')->name('contract-positions.update');
		Route::delete('contract-positions/{position}', 'PositionController@destroy')->name('contract-positions.delete');
		Route::put('/contracts/{contract}/restore', 'ContractController@restore')->name('contracts.restore');
		Route::resource('contracts', 'ContractController');

		// Addendums
		// Route::get('/contracts/{contract}/addendums/create', 'ContractAddendumController@create')->name('contracts.addendums.create');
		// Route::post('/contracts/{contract}/addendums/store', 'ContractAddendumController@store')->name('contracts.addendums.store');
		// Route::get('/contracts/{contract}/addendums/{addendum}/show', 'ContractAddendumController@show')->name('contracts.addendums.show');
		// Route::put('/contracts/{contract}/addendums/{addendum}/update', 'ContractAddendumController@update')->name('contracts.addendums.update');
		// Route::delete('/contracts/{contract}/addendums/{addendum}/destroy', 'ContractAddendumController@destroy')->name('contracts.addendums.destroy');
	});


	Route::prefix('inventories')->namespace('Inventory')->name('inventories.')->group(function () {


		// Proposal


		Route::put('/procurements/{procurement}/restore', 'ProcurementController@restore')->name('procurements.restore');
		Route::resource('procurements', 'ProcurementController')->parameters(['procurements' => 'procurement']);

		// Proposal
		// Route::put('/suppliers/{supplier}/restore', 'SupplierController@restore')->name('suppliers.restore');
		// Route::resource('suppliers', 'SupplierController')->parameters(['suppliers' => 'supplier']);
		Route::resource('land', 'LandController')->parameters(['land' => 'land']);
		Route::resource('building', 'BuildingController')->parameters(['building' => 'building']);
		Route::resource('tool', 'ToolController')->parameters(['tool' => 'tool']);
		Route::resource('vehcile', 'VehcileController')->parameters(['vehcile' => 'vehcile']);
		Route::resource('vehcile_sell', 'VehcileSellController')->parameters(['vehcile_sell' => 'vehcile_sell']);
		Route::resource('tool_sell', 'ToolSellController')->parameters(['tool_sell' => 'tool_sell']);
		Route::resource('buildings_lands', 'BuildingsLandsController')->parameters(['buildings_lands' => 'buildings_lands']);
		Route::resource('lend_buildings_lands', 'LendBuildingsLandsController')->parameters(['lend_buildings_lands' => 'lend_buildings_lands']);

		Route::resource('buildings_lands_floor', 'BuildingsLandsFloorController')->parameters(['buildings_lands_floor' => 'buildings_lands_floor']);
		Route::resource('buildings_lands_room', 'BuildingsLandsRoomController')->parameters(['buildings_lands_room' => 'buildings_lands_room']);
		Route::resource('mutation_vehcile', 'MutationVehcileController')->parameters(['mutation_vehcile' => 'mutation_vehcile']);
		Route::resource('mutation_tool', 'MutationToolController')->parameters(['mutation_tool' => 'mutation_tool']);
		Route::resource('floor', 'FloorController')->parameters(['floor' => 'floor']);
		Route::resource('room', 'RoomController')->parameters(['room' => 'room']);
		Route::resource('lend_vehcile', 'LendVehcileController')->parameters(['lend_vehcile' => 'lend_vehcile']);
		Route::resource('lend_tool', 'LendToolController')->parameters(['lend_tool' => 'lend_tool']);

		// Items
		Route::get('/items/export', 'ItemController@export')->name('items.export');
		Route::get('/items/{item}/print', 'ItemController@print')->name('items.print');
		Route::put('/items/{item}/restore', 'ItemController@restore')->name('items.restore');
		Route::put('/items/{item}/condition', 'ItemController@condition')->name('items.condition');
		Route::put('/items/{item}/attachment', 'ItemController@attachment')->name('items.attachment');
		Route::delete('/items/{item}/remove-attachment', 'ItemController@removeAttachment')->name('items.remove-attachment');
		Route::resource('items', 'ItemController')->parameters(['items' => 'item']);

		// Control
		Route::prefix('controls')->namespace('Control')->name('controls.')->group(function () {
			Route::resource('approval', 'ApprovalController')->parameters(['approval' => 'approvable'])->only(['update']);
			Route::resource('purchases', 'PurchaseController')->parameters(['purchases' => 'item']);
			Route::resource('restocks', 'RestockController')->parameters(['restocks' => 'item'])->only(['index', 'show', 'update']);
			Route::resource('disposes', 'DisposeController')->parameters(['disposes' => 'item'])->only(['index', 'show', 'update']);
			Route::resource('repairs', 'RepairController')->parameters(['repairs' => 'item']);
			Route::resource('others', 'OtherController')->parameters(['others' => 'item'])->only(['index', 'show', 'update']);
			Route::resource('sales', 'SaleController')->parameters(['sales' => 'sale']);
		});

		// leases
		Route::prefix('lease')->namespace('Lease')->name('lease.')->group(function () {
			// manage
			Route::get('/manages/{manage}/print', 'PrintController@index')->name('manages.print');
			Route::put('/manages/{manage}/restore', 'ManageController@restore')->name('manages.restore');
			Route::put('/manages/{approvable}/update', 'ManageController@update')->name('manages.update');
			Route::put('/manages/{manage}/revert/{item}', 'ManageController@revert')->name('manages.revert');
			Route::put('/manages/{manage}/revert-all', 'ManageController@revertAll')->name('manages.revert-all');
			Route::delete('/manages/{manage}/kill', 'ManageController@kill')->name('manages.kill');
			Route::resource('manages', 'ManageController')->parameters(['manage' => 'item'])->except('update');
			// items
			Route::get('/items/{borrow}/show', 'ItemController@show')->name('items.show');
			Route::resource('items', 'ItemController')->parameters(['item' => 'item'])->only('index');
		});
	});
});
