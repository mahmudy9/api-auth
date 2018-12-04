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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/admin' , 'AdminController@index');
Route::get('/admin/new-client' , 'AdminController@new_client');
Route::post('/admin/new-client' , 'AdminController@create_client');
Route::get('/admin/updateclient/{id}' , 'AdminController@update_client');
Route::put('/admin/updateclientdata' , 'AdminController@update_client_data');
Route::get('/admin/deleteclient/{id}' , 'AdminController@delete_client');
Route::delete('/admin/destroyclient' , 'AdminController@destroy_client');
