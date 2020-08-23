<?php
Route::group(['prefix' => 'v1'], function () {
    Route::resource('users', 'UserController');
    Route::group(['prefix' => 'users', 'middleware' => 'auth:api'], function () {
        Route::get('/', 'UserController@users');
        Route::get('search', 'UserController@search');
    });
});
