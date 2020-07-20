<?php

Route::group(['prefix' => 'api', 'middleware' => 'api'], function () {
    
    Route::group(['prefix' => 'product', 'namespace' => 'LaraBase\Store\Controllers'], function () {
        
        Route::get('search', 'ProductController@search');
    
    });
    
});
