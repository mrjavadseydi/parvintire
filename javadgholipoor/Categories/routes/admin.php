<?php
Route::get('categories/{id}/destroy/confirm', 'CategoryController@destroyConfirm')
    ->name('categories.destroy.confirm');
Route::resource('categories', 'CategoryController');
