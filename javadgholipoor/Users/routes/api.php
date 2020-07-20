<?php

Route::group(['prefix' => 'api', 'middleware' => 'api'], function () {
    
    Route::group(['prefix' => 'users', 'namespace' => 'LaraBase\Users\Controllers'], function () {
        
        Route::get('search', 'UserController@search');
        
    });
    
});
