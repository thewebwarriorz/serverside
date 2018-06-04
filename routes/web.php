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
    return view('home');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

/**
 * Admin routes
 */
Route::group(['middleware' => ['auth']], function () {
    Route::get('/update', 'UpdateServersController@index');
    Route::get('/update/do-update', 'UpdateServersController@doUpdate');
    Route::get('/servers', 'ServersController@index');
    Route::get('/servers/statistic/{id}', 'ServersController@statistic');
    Route::get('/servers/chart/{id}', 'ServersController@chart');
});