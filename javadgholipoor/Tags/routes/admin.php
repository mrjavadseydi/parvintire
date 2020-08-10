<?php
Route::get('tags/{id}/destroy/confirm', 'TagController@destroyConfirm')->name('tags.destroy.confirm');
Route::resource('tags', 'TagController');
