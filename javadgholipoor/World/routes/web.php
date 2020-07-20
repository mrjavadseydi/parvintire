<?php

Route::group(['as' => 'admin.', 'prefix' => 'admin', 'middleware' => 'auth:web', 'namespace' => 'LaraBase\World\Controllers'], function () {
    
    Route::group(['prefix' => 'world'], function () {
        Route::get('sql', 'WorldController@sql');
    });
    
});
