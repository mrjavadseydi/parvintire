<?php
Route::group(['prefix' => 'users'], function () {

    Route::get('/', 'ApiController@users');
    Route::get('{id}', 'ApiController@user');
    Route::get('search', 'UserController@search');

});
