<?php

Route::group(['namespace' => 'LaraBase\NewsLetters\Controllers'], function () {
    Route::post('addNewsLetters', 'NewsLettersController@store')->name('addNewsLetters');
});

Route::group(['as' => 'admin.', 'prefix' => 'admin', 'middleware' => 'auth:web', 'namespace' => 'LaraBase\NewsLetters\Controllers'], function () {
    
//    Route::get('comments/{id}/destroy/confirm', 'CommentController@destroyConfirm')->name('comments.destroy.confirm');
//    Route::get('comments/{id}/publish', 'CommentController@publish')->name('comments.publish');
//    Route::resource('comments', 'CommentController');
    
});
