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
Route::get('/admin/destination/edit/{id}', 'DestinationController@editDestination')->name('admin.destination.edit');
Route::post('/admin/destination/store', 'DestinationController@storeDestination')->name('admin.destination.store');
Route::post('/admin/destination/storeImg', 'DestinationController@storeImgDestination')->name('admin.destination.storeImg');
Route::post('/admin/destination/deleteImg', 'DestinationController@deleteImgDestination')->name('admin.destination.deleteImg');
Route::post('/admin/destination/delete', 'DestinationController@deleteDestination')->name('admin.destination.delete');

Route::get('/admin/package/list', 'PackageController@listPackage')->name('admin.package.list');
Route::get('/admin/package/new', 'PackageController@newPackage')->name('admin.package.new');
Route::get('/admin/package/edit/{id}', 'PackageController@editPackage')->name('admin.package.edit');
Route::post('/admin/package/store', 'PackageController@storePackage')->name('admin.package.store');
Route::post('/admin/package/storeImg', 'PackageController@storeImgPackage')->name('admin.package.storeImg');
Route::post('/admin/package/deleteImg', 'PackageController@deleteImgPackage')->name('admin.package.deleteImg');
Route::post('/admin/package/delete', 'PackageController@deletePackage')->name('admin.package.delete');
Route::post('/admin/package/findTag', 'PackageController@findTagPackage')->name('admin.package.findTag');

Route::get('/admin/activity/list', 'ActivityController@listActivity')->name('admin.activity.list');
Route::post('/admin/activity/store', 'ActivityController@storeActivity')->name('admin.activity.store');
Route::post('/admin/activity/doEdit', 'ActivityController@doEditActivity')->name('admin.activity.doEdit');
Route::post('/admin/activity/delete', 'ActivityController@deleteActivity')->name('admin.activity.delete');
Route::post('/admin/activity/check', 'ActivityController@checkActivity')->name('admin.activity.check');


Route::post('/admin/addCity', 'AdminController@addCity')->name('admin.addCity');


Route::post('findCity', 'AjaxController@findCity')->name('findCity');
Route::post('findTag', 'AjaxController@findTag')->name('findTag');
