<?php
Route::group(['prefix' => 'options'], function () {
    Route::get('store', 'OptionController@store')->name('options.store');
});

Route::get('orders/suspends', 'OrderController@suspends')->name('orders.suspends');
Route::resource('orders', 'OrderController');
