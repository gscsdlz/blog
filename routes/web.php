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
    Route::get('/', 'Admin\IndexController@login');
    Route::post('checkEmail', 'IndexController@checkEmail');
    Route::post('login', 'IndexController@doLogin');
});

Route::group(['prefix' => 'admin', 'middleware' => 'admin', 'namespace' => 'Admin'], function(){
    Route::get('index', function(){
        return view('admin.layout');
    });
    Route::get('blog/edit', 'BlogController@edit');
    Route::post('imgUpload', 'UploadController@image');

});