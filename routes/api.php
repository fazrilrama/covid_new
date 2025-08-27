<?php

use Illuminate\Http\Request;

Route::group(['prefix' => 'v1', 'middleware' => ['auth:api']], function(){
    // Route::post('set-project', 'API\V1\UserController@setProject');
    // Route::get('/my-warehouses', 'Api\V1\WarehouseController@myWarehouse');
    Route::get('me', 'API\V1\UserController@me');

    /**
     * TALLY SHEET
     */
    Route::get('inbound-ts-list', 'API\V1\InboundTsController@index');
    Route::post('set-ts', 'API\V1\InboundTsController@setTs');
    
    /**
     * PUT AWAY
     */
    Route::get('inbound-pa-list', 'API\V1\InboundPaController@index');
    Route::post('set-pa', 'API\V1\InboundPaController@setPa');
});