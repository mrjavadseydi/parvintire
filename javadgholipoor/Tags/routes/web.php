<?php

Route::group(['as' => 'admin.', 'prefix' => 'admin', 'middleware' => 'auth:web', 'namespace' => 'LaraBase\Tags\Controllers'], function () {
    
    Route::get('tags/{id}/destroy/confirm', 'TagController@destroyConfirm')->name('tags.destroy.confirm');
    Route::resource('tags', 'TagController');
    
});
