<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
$temps = array();
Route::get('/warehouses/maps_api','WarehouseController@maps_api');
Route::get('/warehouses/maps_api/activity','WarehouseController@maps_api_activity');
Route::get('/warehouses/maps_api/activity_table','WarehouseController@activity_table');
Route::get('/permission/{id}', 'UserController@getDataPermission')->name('permission');
Route::get('/permissionedit/{id}', 'UserController@getDataPermissionedit')->name('permissionedit');
Route::get('/api/daily_logins/{day?}', 'ReportPublicController@daily_logins')->name('daily_logins');
Route::get('/api/wp_items', 'ReportPublicController@wpItems')->name('wpItems');
Route::get('user-activation/{id}', 'UserController@activation');
Route::get('/', function () {  if(auth()->guest()) { return view('auth.login'); } else { return redirect('/home'); } });
Route::get('/control-method', 'StockTransportDetailController@controlMethod');
Auth::routes(['register' => false]);
Route::get('/unlock_account', 'Auth\LoginController@unlock');
Route::post('/unlock_account', 'Auth\LoginController@unlock_post');
Route::get('/unlock_account/{id}', 'Auth\LoginController@unlock_verification');

Route::get('api/get_token_sso', 'BGRAccessController@getToken');
Route::get('api/login_sso', 'BGRAccessController@login');
// public access
Route::get('/reportmutationpublic', 'ReportPublicController@reportMutationPublic')->name('reportmutationpublic');
Route::get('/reportmutationpublic', 'ReportPublicController@reportMutationPublic')->name('reportmutationpublic');
Route::get('{stock_delivery}/information_public', 'ReportPublicController@reportFromQr')->name('reportFromQr');
Route::middleware(['auth'])->group(function(){
    Route::get('/forcepassword', 'ForcePasswordController@index')->name('forcepassword');
    Route::post('/forcepassword/post', 'ForcePasswordController@updatePassword')->name('forcepasswordpost');
});


Route::prefix('advance_notice')->group(function() {
    Route::get('/testing', 'V2AdvanceNoticeController@index');

    
});

Route::middleware(['auth','first-time-login'])->group(function () {
    //data ajax
    Route::get('get_city',
        ['as' => 'get_city' , 'uses' => 'PartyController@get_city'] 
    );

    Route::get('get_region_city',
        ['as' => 'get_region_city' , 'uses' => 'WarehouseController@get_region_city'] 
    );

    Route::get('get_warehouse_officer',
        ['as' => 'get_warehouse_officer' , 'uses' => 'AdvanceNoticeController@get_warehouse_officer'] 
    );

    Route::prefix('ajax')->group(function () {
        Route::get('/get_warehouse_project/{party_id}', 'ProjectController@getWarehouse');
        Route::get('/get_storage_project/{warehouse_id}', 'ProjectController@getStorage');
        Route::get('/get_item_sed/{id}', 'StockEntryDetailController@getDataItem');
        Route::get('/get_control_date', 'StockEntryDetailController@getControlDate');
    });

    Route::get('to_storage_list/{type}',['as' => 'to_storage_list' , 'uses' => 'AdvanceNoticeController@to_storage_list']);
    Route::put('change_storage_status', ['as' => 'change_storage_status' , 'uses' => 'AdvanceNoticeController@change_storage_status']);
    Route::middleware(['role:Superadmin|WarehouseManager'])->get('to_add_project_storage/{project}', ['as' => 'to_add_project_storage' , 'uses' => 'ProjectController@to_add_project_storage']);
    Route::middleware(['role:Superadmin|WarehouseManager'])->post('add_project_storage', ['as' => 'add_project_storage' , 'uses' => 'ProjectController@add_project_storage']);
    Route::middleware(['role:Superadmin|WarehouseManager'])->delete('delete_project_storage', ['as' => 'delete_project_storage' , 'uses' => 'ProjectController@delete_project_storage']);

    Route::get('to_import',['as' => 'to_import' , 'uses' => 'ImportController@to_import']);
    Route::post('import',['as' => 'import' , 'uses' => 'ImportController@import']);

    Route::prefix('stock_transfer_order')->name('stock_transfer_order.')->group(function () {
        Route::get('get_json/{id}', 'StockTransferOrderController@getJson');
        Route::post('/', 'StockTransferOrderController@store')->name('store');
        Route::get('/create/{type}', 'StockTransferOrderController@create')->name('create');
        Route::get('/{type}', 'StockTransferOrderController@index')->name('index');
        Route::put('/{advance_notice}', 'StockTransferOrderController@update')->name('update');
        Route::delete('/{advance_notice}', 'StockTransferOrderController@destroy')->name('destroy');
        Route::get('/{advance_notice}/show', 'StockTransferOrderController@show')->name('show');
        Route::get('/{advance_notice}/edit', 'StockTransferOrderController@edit')->name('edit');
        Route::get('/{advance_notice}/closed', 'StockTransferOrderController@closed')->name('closed');
        Route::post('/{advance_notice}/completed', 'StockTransferOrderController@completed')->name('completed');
        Route::post('/{advance_notice}/closed', 'StockTransferOrderController@closed')->name('closed');
        Route::get('/getspk/{contractid}', 'StockTransferOrderController@getDataSpk')->name('spk');
        Route::post('/assign_to/{advance_notice}', 'StockTransferOrderController@assignTo')->name('assignto');
        Route::get('/{advance_notice}/print_sptb', 'StockTransferOrderController@print_sptb')->name('print_sptb');
    });

    Route::middleware(['role:Superadmin|Admin-BGR|WarehouseManager'])->get('to_add_user/{warehouse}', ['as' => 'to_add_user' , 'uses' => 'WarehouseController@to_add_user']);
    Route::middleware(['role:Superadmin|Admin-BGR|WarehouseManager'])->post('add_user', ['as' => 'add_user' , 'uses' => 'WarehouseController@add_user']);
    Route::middleware(['role:Superadmin|Admin-BGR|WarehouseManager'])->delete('delete_warehouse_officer', ['as' => 'delete_warehouse_officer' , 'uses' => 'WarehouseController@delete_user']);

    Route::get('/empty-project', function(){return view('empty-project');})->name('empty-project');

    Route::get('search/autocomplete', 'SearchController@autocomplete');
    Route::middleware(['role:Superadmin|CommandCenter|Admin-BGR'])->get('/user_locked', 'UserLockedController@index')->name('user-locked');
    Route::middleware(['role:Superadmin|CommandCenter|Admin-BGR'])->get('/user_locked/unlock/{user_id}', 'UserLockedController@unlock')->name('user-locked-unlock');
    Route::middleware(['role:Superadmin|CommandCenter|Admin-BGR'])->get('/user_logged_in', 'UserLockedController@logged_in')->name('user-logged-in');
    Route::middleware(['role:Superadmin|CommandCenter|Admin-BGR'])->get('/user_logged_in/unlock/{user_id}', 'UserLockedController@unlock_logged_in')->name('user-logged-in-unlock');
    
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/profile', 'HomeController@profile')->name('profile');
    Route::post('/profile', 'HomeController@updateProfile')->name('profile');
    Route::get('/profile/project/{project}', 'HomeController@setCurrentProject');
    Route::get('activity_logs', 'HomeController@activity_logs')->name('activity_logs');
    Route::get('cities/get-list', 'CityController@getList');

    // SUHU
    Route::get('/suhu', 'SuhuController@index')->name('suhu');
    Route::post('/suhu/store', 'SuhuController@store')->name('suhu_store');
    Route::post('/suhu/update', 'SuhuController@update')->name('suhu_update');
    Route::post('/suhu/remove', 'SuhuController@remove')->name('suhu_remove');
    Route::get('/suhu/get', 'SuhuController@get')->name('suhu_get');

    Route::post('/warehouses/update_add/{id}', 'WarehouseController@update_add')->name('warehouses.update_add');

    Route::middleware(['role:Superadmin|Admin-BGR'])->resource('cities', 'CityController');

    Route::get('activity_logs/print', 'HomeController@activity_logs_print');
    Route::get('companies/{company}/users', 'CompanyController@users');

    Route::middleware(['role:Superadmin|CommandCenter|Admin-BGR'])->resource('companies', 'CompanyController');
    Route::middleware(['role:Superadmin|CommandCenter|Admin-BGR'])->resource('users', 'UserController');

    route::middleware(['role:Superadmin|Admin-BGR'])->post('/post/permission/, UserController@update_permission');
    // Route::resource('roles', 'RoleController');

    Route::middleware(['role:Superadmin|Admin-BGR'])->put('users/{user}/projects', 'UserController@update_projects')->name('users.update_projects');
    Route::middleware(['role:Superadmin|Admin-BGR'])->get('users/{user}/projects', 'UserController@edit_projects')->name('users.edit_projects');

    Route::middleware(['role:Superadmin|Admin-BGR'])->resource('roles', 'RoleController');

    Route::get('parties/get_json/{id}', 'PartyController@getJson');
    Route::get('parties/get-list', 'PartyController@getPartyList');
    Route::middleware(['role:Superadmin|WarehouseManager|Admin-BGR'])->resource('parties', 'PartyController');
    
    Route::middleware(['role:Superadmin|WarehouseManager|CommandCenter|Admin-BGR'])->resource('projects', 'ProjectController');
    Route::middleware(['role:Superadmin|WarehouseManager|Admin-BGR'])->put('projects/{project}/users', 'ProjectController@update_users')->name('projects.update_users');
    Route::middleware(['role:Superadmin|WarehouseManager|Admin-BGR'])->get('projects/{project}/users', 'ProjectController@users');
    Route::middleware(['role:Superadmin|Admin-BGR'])->resource('party_types', 'PartyTypeController');
    Route::middleware(['role:Superadmin|WarehouseManager|WarehouseSupervisor|Admin-BGR|SPI'])->get('warehouses/maps', 'WarehouseController@maps');
    Route::middleware(['role:CargoOwner|WarehouseSupervisor'])->get('warehouses/get-list', 'WarehouseController@getList');
    Route::middleware(['role:Superadmin|WarehouseManager|WarehouseSupervisor|Admin-BGR|SPI'])->resource('warehouses', 'WarehouseController');
    
    
    Route::middleware(['role:Superadmin|WarehouseManager|Admin-BGR'])->resource('commodities', 'CommodityController');
    Route::middleware(['role:Superadmin|WarehouseSupervisor|Admin-BGR'])->get('storages/stocks', 'StorageController@stocks');
    Route::middleware(['role:Superadmin|WarehouseSupervisor|Admin-BGR'])->get('storages/{storage}/entries', 'StorageController@entries');
    Route::middleware(['role:Superadmin|WarehouseManager|Admin-BGR'])->resource('spks', 'SpkController');
    Route::middleware(['role:Superadmin|WarehouseManager|Admin-BGR'])->resource('contracts', 'ContractController');
    Route::middleware(['role:Superadmin|WarehouseManager|Admin-BGR'])->put('contracts/{contract}/warehouses', 'ContractController@update_warehouses')->name('contracts.update_warehouses');
    Route::middleware(['role:Superadmin|WarehouseManager|Admin-BGR'])->get('contracts/{contract}/warehouses', 'ContractController@edit_warehouses')->name('contracts.edit_warehouses');

    Route::middleware(['role:Superadmin|WarehouseManager|WarehouseSupervisor|Admin-BGR'])->resource('storages', 'StorageController');
    
    Route::middleware(['role:Superadmin|WarehouseManager|WarehouseSupervisor|Admin-BGR'])->resource('items', 'ItemController');
    Route::middleware(['role:Superadmin|Admin-BGR'])->resource('uoms', 'UomController');
    Route::middleware(['role:Superadmin|WarehouseManager|Admin-BGR'])->resource('types', 'TypeController');
    Route::middleware(['role:Superadmin|WarehouseManager|Admin-BGR'])->resource('packings', 'PackingController');
    Route::middleware(['role:Superadmin|Admin-BGR'])->resource('company_types', 'CompanyTypeController');
    Route::middleware(['role:Superadmin|Admin-BGR'])->resource('control_methods', 'ControlMethodController');
    Route::middleware(['role:Superadmin|Admin-BGR'])->resource('transport_types', 'TransportTypeController');
    Route::resource('uom_conversions', 'UomConversionController');
    Route::middleware(['role:Superadmin|Admin-BGR'])->resource('advance_notice_activities', 'AdvanceNoticeActivityController');
    Route::middleware(['role:Superadmin|Admin-BGR'])->resource('regions', 'RegionController');

    Route::put('toggle-arrived/{aon}', 'AdvanceNoticeController@toggleIsArrived')->name('toggle.is-arrived');

    Route::prefix('advance_notices')->name('advance_notices.')->group(function () {
        Route::get('get_json/{id}', 'AdvanceNoticeController@getJson');
        Route::post('/', 'AdvanceNoticeController@store')->name('store');
        Route::get('/create/{type}', 'AdvanceNoticeController@create')->name('create');
        Route::middleware(['role:CargoOwner|WarehouseManager|WarehouseSupervisor|Reporting'])->get('/{type}', 'AdvanceNoticeController@index')->name('index');
        Route::middleware(['role:CargoOwner|WarehouseSupervisor'])->put('/{advance_notice}', 'AdvanceNoticeController@update')->name('update');
        Route::middleware(['role:CargoOwner|WarehouseSupervisor'])->delete('/{advance_notice}', 'AdvanceNoticeController@destroy')->name('destroy');
        Route::middleware(['role:CargoOwner|WarehouseManager|WarehouseSupervisor|Reporting'])->get('/{advance_notice}/show', 'AdvanceNoticeController@show')->name('show');
        Route::middleware(['role:CargoOwner|WarehouseManager|WarehouseSupervisor'])->post('datatable/{type}', 'AdvanceNoticeApiController@dataTables')->name('datatable');
        Route::middleware(['role:CargoOwner|WarehouseSupervisor'])->get('/{advance_notice}/edit', 'AdvanceNoticeController@edit')->name('edit');
        Route::middleware(['role:WarehouseSupervisor'])->get('/{advance_notice}/closed', 'AdvanceNoticeController@closed')->name('closed');
        Route::post('/{advance_notice}/completed', 'AdvanceNoticeController@completed')->name('completed');
        Route::middleware(['role:WarehouseSupervisor'])->post('/{advance_notice}/closed', 'AdvanceNoticeController@closed')->name('closed');
        Route::get('/getspk/{contractid}', 'AdvanceNoticeController@getDataSpk')->name('spk');
        Route::middleware(['role:WarehouseManager'])->post('/assign_to/{advance_notice}', 'AdvanceNoticeController@assignTo')->name('assignto');
        Route::middleware(['role:WarehouseManager'])->post('/validation/{advance_notice}', 'AdvanceNoticeController@validation')->name('validation');
        Route::middleware(['role:CargoOwner|WarehouseManager|Reporting'])->get('/{advance_notice}/print_sptb', 'AdvanceNoticeController@print_sptb')->name('print_sptb');
        Route::middleware(['role:WarehouseSupervisor'])->get('/{advance_notice}/print_unloading', 'AdvanceNoticeController@print_unloading')->name('print_unloading');
        Route::middleware(['role:WarehouseSupervisor'])->get('/{advance_notice}/print_ba', 'AdvanceNoticeController@print_ba')->name('print_ba');
    });
    
    Route::prefix('advance_notice_details')->name('advance_notice_details.')->middleware(['role:CargoOwner|WarehouseSupervisor'])->group(function () {
        Route::get('/create/{advance_notice_id?}', 'AdvanceNoticeDetailController@create')->name('create');
        Route::get('/item/{id}', 'AdvanceNoticeDetailController@getDataItem')->name('item');
    });
    Route::middleware(['role:CargoOwner|WarehouseSupervisor'])->resource('advance_notice_details', 'AdvanceNoticeDetailController')->except(['create']);

    Route::prefix('advance_notice_details_sto')->name('advance_notice_details_sto.')->group(function () {
        Route::get('/create/{advance_notice_id?}', 'AdvanceNoticeDetailStoController@create')->name('create');
        Route::get('/item/{id}', 'AdvanceNoticeDetailStoController@getDataItem')->name('item');
    });
    Route::resource('advance_notice_details_sto', 'AdvanceNoticeDetailStoController')->except(['create']);

    Route::prefix('stock_transports')->name('stock_transports.')->group(function () {
        Route::post('sendAPI/{stock_transport}', 'StockTransportController@sendAPI')->name('sendapistocktransport');
        Route::get('get_json/{id}', 'StockTransportController@getJson');
        
        Route::middleware(['role:WarehouseSupervisor'])->get('/copy_details/{stock_transport}', 'StockTransportController@copyDetails');
        Route::middleware(['role:WarehouseSupervisor'])->post('/', 'StockTransportController@store')->name('store');
        Route::middleware(['role:WarehouseSupervisor'])->get('/create/{type}', 'StockTransportController@create')->name('create');
        Route::middleware(['role:WarehouseManager|WarehouseOfficer|WarehouseSupervisor|Reporting'])->get('/{type}', 'StockTransportController@index')->name('index');
        Route::middleware(['role:WarehouseSupervisor'])->put('/{stock_transport}', 'StockTransportController@update')->name('update');
        Route::middleware(['role:WarehouseSupervisor'])->delete('/{stock_transport}', 'StockTransportController@destroy')->name('destroy');
        Route::middleware(['role:WarehouseSupervisor'])->get('/{stock_transport}/edit', 'StockTransportController@edit')->name('edit');
        Route::middleware(['role:WarehouseManager|WarehouseOfficer|WarehouseSupervisor|Reporting'])->get('/{stock_transport}/show', 'StockTransportController@show')->name('show');
        Route::get('/{id}/json', 'StockEntryController@getJsonData');
        Route::middleware(['role:WarehouseOfficer|WarehouseSupervisor|Reporting'])->get('/{stock_transport}/print', 'StockTransportController@print')->name('print');
        Route::middleware(['role:WarehouseSupervisor'])->post('/{stock_transport}/completed', 'StockTransportController@completed')->name('completed');
    });

    Route::prefix('stock_transport_details')->name('stock_transport_details.')->middleware(['role:WarehouseSupervisor'])->group(function () {
        Route::get('/create/{stock_transport_id?}', 'StockTransportDetailController@create')->name('create');
    });

    Route::middleware(['role:WarehouseSupervisor'])->get('stock_transport_details/{stock_transport_id}/edit', 'StockTransportDetailController@edit');
    Route::middleware(['role:WarehouseSupervisor'])->get('stock_transport_details/{stock_transport_id}/show', 'StockTransportDetailController@show');
    Route::middleware(['role:WarehouseSupervisor'])->resource('stock_transport_details', 'StockTransportDetailController')->except(['create']);

    Route::middleware(['role:WarehouseSupervisor'])->put('update_actual/{std_id}', ['as' => 'update_actual' , 'uses' => 'StockTransportDetailController@updateActual']);
    // Route::middleware(['role:WarehouseSupervisor'])->put('stock_transport_details/update/{std_id}', ['uses' => 'StockTransportDetailController@update']);

    Route::middleware(['role:WarehouseSupervisor'])->put('stock_transport_details/{stock_transport_id}/update_actual', 'StockTransportDetailController@updateActual');
    Route::middleware(['role:WarehouseSupervisor'])->put('stock_transport_details/{stock_transport_id}/update', 'StockTransportDetailController@update');

    Route::prefix('stock_entries')->name('stock_entries.')->group(function () {

        Route::get('get_json/{id}', 'StockEntryController@getJson');
        Route::middleware(['role:WarehouseSupervisor'])->get('/copy_details/{stock_entry}', 'StockEntryController@copyDetails');
        Route::middleware(['role:WarehouseSupervisor'])->post('/', 'StockEntryController@store')->name('store');
        Route::middleware(['role:WarehouseSupervisor'])->get('/create/{type}', 'StockEntryController@create')->name('create');
        Route::middleware(['role:WarehouseManager|WarehouseOfficer|WarehouseSupervisor|Transporter'])->get('/{type}', 'StockEntryController@index')->name('index');
        Route::middleware(['role:WarehouseSupervisor'])->put('/{stock_entry}', 'StockEntryController@update')->name('update');
        Route::middleware(['role:WarehouseSupervisor'])->delete('/{stock_entry}', 'StockEntryController@destroy')->name('destroy');
        Route::middleware(['role:WarehouseSupervisor'])->get('/{stock_entry}/edit', 'StockEntryController@edit')->name('edit');
        Route::middleware(['role:WarehouseSupervisor'])->get('/status/{id}/{status}', 'StockEntryController@statusUpdate')->name('status-update');
        Route::middleware(['role:WarehouseSupervisor'])->get('/{id}/print_picking_plan', 'StockEntryController@printPickingPlan')->name('print_picking_plan');
        Route::middleware(['role:WarehouseManager|WarehouseOfficer|WarehouseSupervisor|Transporter'])->get('/{stock_entry}/show', 'StockEntryController@show')->name('show');
        Route::middleware(['role:WarehouseSupervisor|Transporter'])->get('/{stock_entry}/print', 'StockEntryController@print')->name('print');
        Route::middleware(['role:WarehouseSupervisor'])->post('/{stock_entry}/{status}', 'StockEntryController@status')->name('status');
        Route::middleware(['role:WarehouseSupervisor'])->post('/{stock_entry}/closed', 'StockEntryController@closed')->name('closed');
        Route::middleware(['role:WarehouseSupervisor'])->get('/{stock_entry}/print_loading_order', 'StockEntryController@print_loading_order');
    });
    Route::prefix('stock_entry_details')->name('stock_entry_details.')->middleware(['role:WarehouseSupervisor'])->group(function () {
        Route::get('/create/{stock_entry_id?}', 'StockEntryDetailController@create')->name('create');
    });
    Route::middleware(['role:WarehouseSupervisor'])->resource('stock_entry_details', 'StockEntryDetailController')->except(['create']);

    Route::prefix('stock_deliveries')->name('stock_deliveries.')->group(function () {
        Route::middleware(['role:WarehouseSupervisor'])->get('/copy_details/{stock_delivery}', 'StockDeliveryController@copyDetails');
        Route::middleware(['role:WarehouseSupervisor'])->post('/', 'StockDeliveryController@store')->name('store');
        Route::middleware(['role:WarehouseSupervisor'])->get('/create/{type}', 'StockDeliveryController@create')->name('create');
        Route::middleware(['role:WarehouseManager|WarehouseOfficer|WarehouseSupervisor|Reporting'])->get('/{type}', 'StockDeliveryController@index')->name('index');
        Route::middleware(['role:WarehouseSupervisor'])->put('/{stock_delivery}', 'StockDeliveryController@update')->name('update');
        Route::middleware(['role:WarehouseSupervisor'])->delete('/{stock_delivery}', 'StockDeliveryController@destroy')->name('destroy');
        Route::middleware(['role:WarehouseSupervisor'])->get('/{stock_delivery}/edit', 'StockDeliveryController@edit')->name('edit');
        Route::middleware(['role:WarehouseManager|WarehouseOfficer|WarehouseSupervisor|Reporting'])->get('/{stock_delivery}/show', 'StockDeliveryController@show')->name('show');
        Route::middleware(['role:WarehouseSupervisor'])->get('/{stock_delivery}/print', 'StockDeliveryController@print')->name('print');
        Route::middleware(['role:WarehouseSupervisor'])->get('/{stock_delivery}/closed', 'StockDeliveryController@closed')->name('closed');
        Route::middleware(['role:WarehouseSupervisor'])->post('/{stock_delivery}/completed', 'StockDeliveryController@completed')->name('completed');
        Route::middleware(['role:WarehouseSupervisor'])->post('/{stock_delivery}/closed', 'StockDeliveryController@closed')->name('closed');
    });
    Route::prefix('stock_delivery_details')->name('stock_delivery_details.')->middleware(['role:WarehouseSupervisor'])->group(function () {
        Route::get('/create/{stock_delivery_id?}', 'StockDeliveryDetailController@create')->name('create');
    });
    Route::middleware(['role:WarehouseSupervisor'])->resource('stock_delivery_details', 'StockDeliveryDetailController')->except(['create']);

    Route::prefix('report')->name('report.')->middleware(['role:CargoOwner|WarehouseManager|WarehouseSupervisor|Admin-BGR|CommandCenter|SPI|Reporting|Transporter'])->group(function() {
        Route::get('stock_on_location', 'ReportController@stockOnLocation')->name('stock-on-location');
        Route::get('stock_on_staging/{type}', 'ReportController@stockOnStaging')->name('stock-on-staging');
        Route::get('management_stock_report', 'ReportController@managementStock')->name('management-stock');
        Route::get('stock_mutation', 'ReportController@stockMutation')->name('stock-mutation');
        Route::get('stock_mutation/print', 'ReportController@printStockMutation')->name('stock-mutation-print');
        Route::get('handling/{type}', 'ReportController@handling')->name('handling');
        Route::get('sku_transaction', 'ReportController@skuTransaction')->name('sku-transaction');
        Route::get('estimated_delivery_notes', 'ReportController@estimatedDeliveryNote');
        Route::get('estimated_delivery_notes/print', 'ReportController@estimatedDeliveryNotePrint')->name('print-edn');
        Route::get('delivery_notes/print', 'ReportController@DeliveryNotePrint');
        Route::get('picking_plan/print', 'ReportController@PickingPlanPrint');
        Route::get('loading_order/print', 'ReportController@LoadingOrderPrint');
        Route::get('stock_mutation/print', 'ReportController@stockMutationPrint');
        Route::get('handling/print/{type}', 'ReportController@handlingPrint');
        Route::get('storages/{id}', 'ReportController@getStorages');
        Route::get('ain_aon_completed', 'ReportController@ainaoncomplete')->name('ainaoncomplete');
        Route::get('warehouse_projects', 'ReportController@projectManagement');
        Route::get('warehouse_contracts', 'ReportController@contractManagement');
        Route::get('storage_location', 'ReportController@stockMutationLocation');
        Route::get('detail_inbound', 'ReportController@detailInbound');
        Route::get('detail_outbound', 'ReportController@detailOutbound');
        Route::get('warehouse_stockOpname', 'ReportController@warehouse_stockOpname')->name('warehouse_stockOpname');
        Route::get('stock_opnames', 'ReportController@stockOpnames');
        Route::get('good_issue_management', 'ReportController@goodreceiveManagement');
        Route::get('good_issue_mutation', 'ReportController@stockDeliverMutation');
        Route::get('detail_good_issue_mutation/{date}/{project}', 'ReportController@detailGoodIssue')->name('detail_good_issue_mutation');
        Route::get('good_issue_mutation_sku', 'ReportController@stockDeliverMutationSKU');
        Route::get('storage_location', 'ReportController@stockMutationLocation');
        Route::get('storage_location_detail', 'ReportController@stockMutationLocationDetail');
        Route::get('stock_mutation_month', 'ReportController@stockMutationMonth');
    });
    ///yang diubah
    Route::post('v1/report/stock_on_location', 'ReportApiController@dataTablesStockOnLocation')->name('report.stock_on_location');
    Route::get('v1/report/filter', 'ReportApiController@masterFilter')->name('report.masterfilter');
    Route::get('v1/report/warehouse-list', 'ReportController@getwarehouseproject')->name('warehouse_list');
    Route::get('v1/report/warehouse-stockopname', 'ReportController@getwarehouse')->name('warehouse_stockopname');
    Route::get('v1/storage_locations', 'ReportController@getStorageWarehouseDetail')->name('api.storage_locations'); 
    Route::get('v1/control_date', 'ReportController@getControlDate')->name('api.control_date'); 


    Route::get('v1/report/item-project', 'ReportController@getItemProject')->name('warehouse_item_project'); 
    Route::get('v1/report/branch', 'ReportController@branch')->name('report_brach');
    Route::get('v1/report/warehouse', 'ReportController@warehouse_list')->name('report_warehouse');
    Route::get('v1/management_stock', 'ReportApiController@managementStock')->name('api.management_stock'); 
    Route::get('v1/storage_location', 'ReportApiController@mutationOnLoction')->name('api.storage_location'); 
    Route::get('v1/storage', 'ReportController@getStoragesWarehouse')->name('api.storage'); 
    Route::get('v1/detail_inbound', 'ReportApiController@detailInbound')->name('api.detail_inbound'); 
    Route::get('v1/detail_outbound', 'ReportApiController@detailOutbound')->name('api.detail_outbound'); 
    Route::get('v1/report/project-list', 'ReportController@getProject')->name('project_list');
    
    Route::middleware(['role:Superadmin|WarehouseManager|CommandCenter|Admin-BGR|SPIReporting'])->get('work_order', 'HomeController@workOrder');
    Route::middleware(['role:Superadmin|WarehouseManager|CommandCenter|Admin-BGR|SPI|Reporting'])->get('warehouse_activity', 'HomeController@warehouseActivity');
    Route::middleware(['role:CommandCenter|Admin-BGR|WarehouseManager|SPI|Reporting'])->get('v1/project_management', 'ReportApiController@projectManagement')->name('api.project_management');
    Route::middleware(['role:CommandCenter|Admin-BGR|WarehouseManager|SPI|Reporting'])->get('v1/contract_management', 'ReportApiController@contractManagement')->name('api.contract_management');
    Route::middleware(['role:CommandCenter|Admin-BGR|WarehouseManager|SPI|Reporting|WarehouseSupervisor|Transporter'])->get('v1/good_issue_management', 'ReportApiController@goodreceiveManagement')->name('api.good_issue_management');
    Route::middleware(['role:CommandCenter|Admin-BGR|WarehouseManager|SPI|Reporting|WarehouseSupervisor|Transporter'])->get('v1/goodreceiveManagementMonitoring', 'ReportApiController@goodreceiveManagementMonitoring')->name('api.good_issue_management_monitoring');
    

    Route::middleware(['role:WarehouseManager|WarehouseSupervisor'])->prefix('stock_opnames')->name('stock_opnames.')->group(function() {
        Route::get('', 'StockOpnameController@index')->name('index');
        Route::get('/create', 'StockOpnameController@create')->name('create');
        Route::get('/{stock_opname}/print', 'StockOpnameController@print')->name('print');

        Route::get('{stock_opname}/edit', 'StockOpnameController@edit')->name('edit');
        Route::post('/', 'StockOpnameController@store')->name('store');

        Route::put('{item}/update', 'StockOpnameController@update')->name('update');
        Route::post('{stock_opname}/completed', 'StockOpnameController@complete')->name('completed');
        Route::get('/{stock_opname}/view', 'StockOpnameController@show')->name('show');
        Route::delete('/{stock_opname}', 'StockOpnameController@destroy')->name('destroy');
        Route::get('/{stock_opname}/export', 'StockOpnameController@export')->name('export');


    });

    Route::middleware(['role:WarehouseManager|WarehouseSupervisor'])->prefix('stock_opname_details')->name('stock_opname_details.')->group(function() {
        Route::get('', 'StockOpnameDetailController@index')->name('index');
        Route::get('/create/{stock_opname}', 'StockOpnameDetailController@create')->name('create');
        Route::get('{stock_opname_detail}/edit', 'StockOpnameDetailController@edit')->name('edit');
        Route::post('/{stock_opname}', 'StockOpnameDetailController@store')->name('store');
        Route::put('{stock_opname_detail}/update', 'StockOpnameDetailController@update')->name('update');
        Route::delete('{stock_opname_detail}', 'StockOpnameDetailController@destroy')->name('destroy');

    });

    // internal movement new

    Route::middleware(['role:WarehouseSupervisor'])->resource('stock_internal_movements','StockInternalMovementController');
    Route::middleware(['role:WarehouseSupervisor'])->prefix('stock_internal_movements')->name('stock_internal_movements.')->group(function(){
        Route::post('/{internal_movement}/complete', 'StockInternalMovementController@completed')->name('completed');
    });

    Route::middleware(['role:WarehouseSupervisor'])->prefix('stock_internal_movement_details')->name('stock_internal_movement_details.')->group(function(){
        Route::get('/create/{id}', 'StockInternalMovementDetailController@create')->name('create');
        Route::get('/{stock_internal_movement_detail}/edit', 'StockInternalMovementDetailController@edit')->name('edit');
        Route::post('/', 'StockInternalMovementDetailController@store')->name('store');
        Route::get('/{storage_id}', 'StockInternalMovementDetailController@detailStorage')->name('storage');
        Route::put('/{stock_internal_movement_detail}', 'StockInternalMovementDetailController@update')->name('update');
        Route::delete('/{stock_internal_movement_detail}', 'StockInternalMovementDetailController@destroy')->name('destroy');
    });


    Route::get('export-warehouse', 'WarehouseController@exportWarehouse');
    Route::get('ew-no-coordinate', 'WarehouseController@exportNoCoordinateWarehouse');
    Route::get('ew-non-active', 'WarehouseController@exportNonActiveWarehouse');

    Route::middleware(['role:Transporter'])->get('deliveries', 'DeliveryController@index')->name('deliveries.index');
    Route::middleware(['role:Transporter'])->get('deliveries_list', 'DeliveryController@DOCompleted')->name('api.gicomplete');
    Route::get('deliveries_list/{stock_delivery}', 'DeliveryController@stockDeliveryDetail')->name('api_stock_delivery_detail');
    Route::middleware(['role:Transporter'])->post('store_status', 'DeliveryController@update')->name('update_status');

});