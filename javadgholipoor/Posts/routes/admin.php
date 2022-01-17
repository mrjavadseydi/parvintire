<?php
Route::get('post-types/{id}/destroy/confirm', 'PostTypeController@destroyConfirm')->name('post-types.destroy.confirm');
Route::get('posts/translate', 'PostController@translate');
Route::post('posts/translate/store', 'PostController@storeTranslate');
Route::resource('posts', 'PostController');
Route::resource('post-types', 'PostTypeController');
