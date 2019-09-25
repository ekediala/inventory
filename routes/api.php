<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::middleware(['api'])->group(function () {
    Route::post('/login', 'AuthController@login');
    Route::post('/logout', 'AuthController@logout');
    Route::post('/refresh', 'AuthController@refresh');
    Route::resource('inventory', 'InventoryController');
    Route::resource('user', 'UserController');
    Route::get('/admin/users', 'AdminController@users');
    Route::get('/admin/inventories', 'AdminController@inventories');
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'password',
], function () {
    Route::post('create', 'PasswordRequestController@create');
    Route::get('find/{token}', 'PasswordRequestController@find');
    Route::post('reset', 'PasswordRequestController@reset');
});

