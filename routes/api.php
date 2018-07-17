<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


//Route::middleware(['auth:api'])->group(function () {
    Route::prefix('v1')->group(function () {
        Route::prefix('users')->group(function () {
            Route::get('/', 'TaskFour\UsersApi@index');
            Route::post('create/', 'TaskFour\UsersApi@create');
            Route::post('update/', 'TaskFour\UsersApi@update');
            Route::post('delete/', 'TaskFour\UsersApi@delete');
        });
    });
//});
