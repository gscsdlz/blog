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

Route::get('all_type', 'IndexController@type_list')->where(['page' => '[0-9]*']);
Route::get('all/{page?}', 'IndexController@blog_list')->where(['page' => '[0-9]*']);
Route::get('type/{type}/{page?}', 'IndexController@blog_types')->where(['page' => '[0-9]*']);
Route::get('blog/{bid}', 'IndexController@blog')->where(['bid' => '[0-9]+']);
Route::get('file/get_markdown/{fname}', function($fname){
   return Storage::get('public/blog/md_file/'.$fname);
});

Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function(){
    Route::get('/', 'IndexController@login');
    Route::post('checkEmail', 'IndexController@checkEmail');
    Route::post('login', 'IndexController@doLogin');
});

Route::group(['prefix' => 'admin', 'middleware' => 'admin', 'namespace' => 'Admin'], function(){
    Route::get('blog/edit/{bid?}', 'BlogController@edit')->where(['bid' => '[0-9]*']);
    Route::post('blog/add', 'BlogController@add');
    Route::any('blog/del', 'BlogController@del');
    Route::get('blog/list', 'BlogController@list_blog');

    Route::post('imgUpload', 'UploadController@image');

    Route::get('type/edit', 'TypeController@edit');
    Route::post('type/change', 'TypeController@change');
    Route::post('type/add', 'TypeController@add');
    Route::post('type/navbar_edit', 'TypeController@navbar');
    Route::post('type/del', 'TypeController@del');




});