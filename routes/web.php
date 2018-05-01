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

Route::get('/',function () {
    return view('welcome');
});
Route::prefix('taskone')->group(function () {
    Route::get('/', 'TaskOne\FileHandlerController@index')->name('taskone');
    Route::post('/uploadfile', 'TaskOne\FileHandlerController@uploadFile')->name('uploadfile');
    Route::get('/getfiledata/{id}', 'TaskOne\FileHandlerController@getFileData')
        ->where('id', '[0-9]+')->name('getfiledata');
    Route::get('/downloadfile/{id}', 'TaskOne\FileHandlerController@downloadFile')
        ->where('id', '[0-9]+')->name('downloadfile');
});
