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

Route::get('/','App\Http\Controllers\UserController@loadView');

Route::group(['prefix' => 'users'], function () {
    Route::get('/','App\Http\Controllers\UserController@loadView');
    Route::get('all','App\Http\Controllers\UserController@getAllUsers');
    Route::post('save','App\Http\Controllers\UserController@saveUser');
    Route::get('validateUser','App\Http\Controllers\UserController@validateUser');
    Route::get('getUserById','App\Http\Controllers\UserController@getUserById');
    Route::get('delete','App\Http\Controllers\UserController@deleteUser');
});

Route::group(['prefix' => 'categories'], function () {
    Route::get('/','App\Http\Controllers\CategoryController@loadView');
    Route::get('all','App\Http\Controllers\CategoryController@getAllCategories');
    Route::post('save','App\Http\Controllers\CategoryController@saveCategory');    
    Route::get('getCategoryById','App\Http\Controllers\CategoryController@getCategoryById');
    Route::get('delete','App\Http\Controllers\CategoryController@deleteCategory');
});

Route::group(['prefix' => 'config'], function () {
    Route::get('/','App\Http\Controllers\ConfigController@loadView');
});
