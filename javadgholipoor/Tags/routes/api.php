<?php

Route::group(['prefix' => 'api', 'middleware' => 'api'], function () {
    
    Route::group(['prefix' => 'tags', 'namespace' => 'LaraBase\Tags\Controllers'], function () {
        
        Route::get('search', 'TagController@search');
        
    });
    
});
