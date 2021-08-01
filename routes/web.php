<?php

use Illuminate\Support\Facades\Route;

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
Route::get('orders/{id}', 'OrderController@show')->name('add');

Route::post('add-order/{id}', 'OrderController@addOrder')->name('addsubmit');

//Админка
    //работа с домами
Route::get('/admin','Admin\HouseController@mainAdminPanel')->name('admin-main-panel');
Route::get('/admin/houses', 'Admin\HouseController@showAllHouses')->name('houses-view');
Route::get('/admin/house-add}',function(){
    return view('admin/house-add');
})->name('admin-house-add');
Route::post('/admin/house-add/update', 'Admin\HouseController@updateHouseAdding')->name('house-update-add');
Route::get('/admin/house-edit/{id}', 'Admin\HouseController@editHouse')->name('admin-house-edit');
Route::post('/admin/house-edit/{id}/update', 'Admin\HouseController@updateHouseChanges')->name('house-update-changes');
    //работа с датами и расписанием
Route::get('admin/schedule-of-dates','Admin\DateController@getWeekScheduleView')->name('schedule-of-dates');
Route::post('admin/schedule-of-dates/update_price',
    'Admin\DateController@updatePrice')->name('update-price');

Route::get('/houses', 'Admin\HouseController@index')->name('house');
Route::get('/houses/{id}', 'Admin\HouseController@show')->name('house_show');

//Route::get('/admin', 'HouseController@showAll')->name('admin-page');

Route::get('/form','GuideFormController@showGuideForm')->name('show-form');

Route::get('store_image', 'PhotoController@index');
Route::post('store_image/insert_photo', 'PhotoController@insertPhoto')->name('insert_photo');
Route::get('store_image/fetch_image/{id}', 'PhotoController@fetchImage');
Route::get('store_image/deleting/{id}', 'PhotoController@deletePhoto')->name('delete_photo');
