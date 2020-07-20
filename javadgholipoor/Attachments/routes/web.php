<?php

Route::group(['as' => 'admin.', 'prefix' => 'admin', 'middleware' => 'auth:web', 'namespace' => 'LaraBase\Attachments\Controllers'], function () {
    
    Route::group(['prefix' => 'attachments'], function (){
    
        Route::get('{id}/edit', 'AttachmentController@edit')->name('attachments.edit');
        Route::post('{id}/update', 'AttachmentController@update')->name('attachments.update');
    
    });
    
});
