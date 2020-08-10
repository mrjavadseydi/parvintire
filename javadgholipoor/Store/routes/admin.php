<?php
Route::group(['prefix' => 'options'], function () {
    Route::get('store', 'OptionController@store')->name('options.store');
});

Route::resource('orders', 'OrderController');
