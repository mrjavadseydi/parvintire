<?php
Route::get('users/verify/{type}/{id}', 'UserController@verify')->name('users.verify');
Route::get('users/{id}/destroy/confirm', 'UserController@destroyConfirm')->name('users.destroy.confirm');
Route::resource('users', 'UserController');
