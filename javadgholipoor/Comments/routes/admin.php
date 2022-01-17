<?php
Route::get('comments/{id}/destroy/confirm', 'CommentController@destroyConfirm')->name('comments.destroy.confirm');
Route::get('comments/{id}/publish', 'CommentController@publish')->name('comments.publish');
Route::resource('comments', 'CommentController');
Route::resource('tickets', 'CommentController');
