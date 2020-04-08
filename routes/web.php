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

use Symfony\Component\HttpFoundation\Request;


Route::get('/', 'MainController@mainPage');

Route::get('/about_us', 'MainController@aboutUs')->name('aboutUs');

Route::post('beforeList', 'MainController@beforeList')->name('beforeList');

Route::get('list/{kind}/{value1}/{value2?}/{value3?}', 'MainController@list')->name('show.list');

Route::post('getListElems', 'MainController@getListElems')->name('getListElems');

Route::get('destination/{categoryId}/{slug}', 'MainController@showDestination')->name('show.destination');

Route::get('package/{destination}/{slug}', 'MainController@showPackage')->name('show.package');

Route::post('findDestination', 'AjaxController@findDestination')->name('findDestination');

Route::post('search', 'AjaxController@search')->name('search');

Auth::routes();

Route::get('journal', 'JournalController@mainPageJournal')->name('journal.index');
Route::get('journal/show/{id}/{slug?}', 'JournalController@showJournalContent')->name('journal.show');
Route::get('journal/list/{kind}/{value?}', 'JournalController@listJournal')->name('journal.list');
Route::post('journal/getListElemes', 'JournalController@getElems')->name('journal.getElems');

Route::middleware(['auth', 'web'])->group(function () {
    Route::get('/admin', 'AdminController@adminIndex')->name('admin.index');

    Route::get('/admin/category/list', 'DestinationController@listCategory')->name('admin.destination.category.index');
    Route::post('/admin/category/store', 'DestinationController@storeCategory')->name('admin.destination.category.store');
    Route::post('/admin/category/edit', 'DestinationController@editCategory')->name('admin.destination.category.edit');
    Route::post('/admin/category/delete', 'DestinationController@deleteCategory')->name('admin.destination.category.delete');
    Route::post('/admin/category/check', 'DestinationController@checkCategoryDestination')->name('admin.destination.category.check');

    Route::post('/admin/category/title/store', 'DestinationController@storeCategoryTitle')->name('admin.destination.category.title.store');
    Route::post('/admin/category/title/delete', 'DestinationController@deleteCategoryTitle')->name('admin.destination.category.title.delete');

    Route::get('/admin/destination/list', 'DestinationController@listDestination')->name('admin.destination.list');
    Route::get('/admin/destination/new', 'DestinationController@newDestination')->name('admin.destination.new');
    Route::get('/admin/destination/edit/{id}', 'DestinationController@editDestination')->name('admin.destination.edit');
    Route::get('/admin/destination/description/{id}', 'DestinationController@descriptionDestination')->name('admin.destination.description');
    Route::post('/admin/destination/store', 'DestinationController@storeDestination')->name('admin.destination.store');
    Route::post('/admin/destination/storeImg', 'DestinationController@storeImgDestination')->name('admin.destination.storeImg');
    Route::post('/admin/destination/storeDescriptionImg', 'DestinationController@storeDescriptionImgDestination')->name('admin.destination.storeDescriptionImg');
    Route::post('/admin/destination/storeDescription', 'DestinationController@storeDescriptionDestination')->name('admin.destination.storeDescription');
    Route::post('/admin/destination/storeVideoAudio', 'DestinationController@storeVideoAudioDestination')->name('admin.destination.storeVideoAudio');
    Route::post('/admin/destination/deleteImg', 'DestinationController@deleteImgDestination')->name('admin.destination.deleteImg');
    Route::post('/admin/destination/delete', 'DestinationController@deleteDestination')->name('admin.destination.delete');
    Route::post('/admin/destination/check', 'DestinationController@checkDestination')->name('admin.destination.check');

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

    Route::get('/admin/journal/category/list', 'JournalAdminController@indexCategory')->name('admin.journal.category.index');
    Route::post('/admin/journal/category/store', 'JournalAdminController@storeCategory')->name('admin.journal.category.store');
    Route::post('/admin/journal/category/edit', 'JournalAdminController@editCategory')->name('admin.journal.category.edit');

    Route::get('/admin/journal/list', 'JournalAdminController@indexJournal')->name('admin.journal.list');
    Route::get('/admin/journal/new', 'JournalAdminController@newJournal')->name('admin.journal.new');
    Route::get('/admin/journal/edit/{id}', 'JournalAdminController@editJournal')->name('admin.journal.edit');
    Route::post('/admin/journal/store/', 'JournalAdminController@storeJournal')->name('admin.journal.store');
    Route::post('/admin/journal/delete', 'JournalAdminController@deleteJournal')->name('admin.journal.delete');
    Route::post('/admin/journal/storeDescriptionImg', 'JournalAdminController@storeDescriptionImgJournal')->name('admin.journal.storeDescriptionImg');
    Route::post('/admin/journal/checkSeo', 'JournalAdminController@checkSeo')->name('admin.journal.checkSeo');

    Route::post('/admin/addCity', 'AdminController@addCity')->name('admin.addCity');
});


Route::post('findCity', 'AjaxController@findCity')->name('findCity');
Route::post('findTag', 'AjaxController@findTag')->name('findTag');

