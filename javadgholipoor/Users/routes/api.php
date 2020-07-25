<?php

Route::group(['prefix' => 'api', 'middleware' => 'api'], function () {

    Route::group(['prefix' => 'users', 'namespace' => 'LaraBase\Users\Controllers'], function () {

        Route::get('/', 'ApiController@users');
        Route::get('{id}', 'ApiController@user');
        Route::get('search', 'UserController@search');

    });

});
