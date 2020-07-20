<?php

Route::group([
    'as'         => 'admin.',
    'prefix'     => 'admin',
    'middleware' => 'auth:web',
    'namespace'  => 'LaraBase\Themes\Controllers'
], function () {
    
    Route::group(['prefix' => 'options'], function () {
        Route::get('themes', 'OptionController@themes')->name('options.themes');
    });
    
});
