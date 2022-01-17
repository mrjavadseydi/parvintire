<?php
Route::get('menus/{id}/destroy/confirm', 'MenuController@destroyConfirm')->name('menus.destroy.confirm');
Route::resource('menus', 'MenuController');

Route::get('menu-items/{id}/destroy/confirm', 'MenuItemController@destroyConfirm')->name('menu-items.destroy.confirm');
Route::resource('menu-items', 'MenuItemController');
