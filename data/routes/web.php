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

Route::get('/', function () {
    return '<h1>Welcome to Seoul</h1>';
});

Route::group([
    'prefix' => 'house',
    'as'     => 'house.',
], function () {
    Route::get('list', [
        'as'   => 'list',
        'uses' => 'HouseController@getList'
    ]);
    Route::get('form', [
        'as'   => 'form',
        'uses' => 'HouseController@form'
    ]);
    Route::post('create', [
        'as'   => 'create',
        'uses' => 'HouseController@create',
    ]);
    Route::get('upload', [
        'as'   => 'upload',
        'uses' => 'HouseController@upload'
    ]);
    Route::get('delete', [
        'as' => 'delete',
        'uses' => 'HouseController@delete',
    ]);
});




Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
