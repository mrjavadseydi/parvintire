<?php
Route::group(['prefix' => 'product'], function () {
    Route::get('search', 'ProductController@search');
});

Route::post('orders/setStatus', 'OrderController@setStatus');
