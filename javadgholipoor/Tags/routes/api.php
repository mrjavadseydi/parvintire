<?php
Route::group(['middleware' => 'auth:api'], function () {
    Route::group(['prefix' => 'v1'], function () {
        Route::group(['prefix' => 'tags'], function () {
            Route::get('search', 'TagController@search');
        });
    });
});
