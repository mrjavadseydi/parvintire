<?php

Route::group(['as' => 'admin.', 'prefix' => 'admin', 'middleware' => 'auth:web', 'namespace' => 'LaraBase\Categories\Controllers'], function () {
    
    Route::get('categories/{id}/destroy/confirm', 'CategoryController@destroyConfirm')->name('categories.destroy.confirm');
    Route::resource('categories', 'CategoryController');
    
});
