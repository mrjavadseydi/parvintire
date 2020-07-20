<?php

Route::group(['namespace' => 'LaraBase\Comments\Controllers'], function () {
    Route::post('addComment', 'CommentController@store')->name('addComment');
});

Route::group(['as' => 'admin.', 'prefix' => 'admin', 'middleware' => 'auth:web', 'namespace' => 'LaraBase\Comments\Controllers'], function () {
    
    Route::get('comments/{id}/destroy/confirm', 'CommentController@destroyConfirm')->name('comments.destroy.confirm');
    Route::get('comments/{id}/publish', 'CommentController@publish')->name('comments.publish');
    Route::resource('comments', 'CommentController');
    Route::resource('tickets', 'CommentController');
    
});
