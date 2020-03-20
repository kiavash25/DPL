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
    return view('mainPage');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::get('/admin', 'AdminController@adminIndex')->name('admin.index');


Route::get('/admin/destination/list', 'DestinationController@listDestination')->name('admin.destination.list');
Route::get('/admin/destination/new', 'DestinationController@newDestination')->name('admin.destination.new');
Route::get('/admin/destination/images/{id}', 'DestinationController@imagesDestination')->name('admin.destination.images');
Route::get('/admin/destination/edit/{id}', 'DestinationController@editDestination')->name('admin.destination.edit');
Route::post('/admin/destination/store', 'DestinationController@storeDestination')->name('admin.destination.store');
Route::post('/admin/destination/storeImg', 'DestinationController@storeImgDestination')->name('admin.destination.storeImg');
Route::post('/admin/destination/deleteImg', 'DestinationController@deleteImgDestination')->name('admin.destination.deleteImg');
Route::post('/admin/destination/delete', 'DestinationController@deleteDestination')->name('admin.destination.delete');


Route::post('/admin/destination/findTag', 'DestinationController@findTagDestination')->name('admin.destination.findTag');

Route::post('/admin/addCity', 'AdminController@addCity')->name('admin.addCity');
Route::post('findCity', 'AjaxController@findCity')->name('findCity');