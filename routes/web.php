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

Route::get('/', 'IndexController@index');

Route::any('/test', 'IndexController@test');

Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function(){
    Route::get('/', 'IndexController@login');
    Route::post('checkEmail', 'IndexController@checkEmail');
    Route::post('login', 'IndexController@doLogin');
});

Route::group(['prefix' => 'admin', 'middleware' => 'admin', 'namespace' => 'Admin'], function(){
    Route::get('blog/edit', 'BlogController@edit');
    Route::post('blog/add', 'BlogController@add');
    Route::post('imgUpload', 'UploadController@image');

    Route::get('type/edit', 'TypeController@edit');
    Route::post('type/change', 'TypeController@change');
    Route::post('type/add', 'TypeController@add');
    Route::post('type/navbar_edit', 'TypeController@navbar');
    Route::post('type/del', 'TypeController@del');

});