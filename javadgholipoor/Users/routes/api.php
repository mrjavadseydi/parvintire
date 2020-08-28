<?php
Route::group(['prefix' => 'v1'], function () {
    Route::resource('users', 'UserController');
    Route::get('users/{id}/block', 'UserController@block');
    Route::get('users/verify/{type}/{id}', 'UserController@verify');
    Route::group(['prefix' => 'users'], function () {
        Route::get('/', 'UserController@users');
        Route::get('search', 'UserController@search');
    });
});
