<?php
Route::group([], function () {
    Route::group(['prefix' => 'v1'], function () {
        Route::group(['prefix' => 'product'], function () {
            Route::get('search', 'ProductController@search');
        });
        Route::post('orders/setStatus', 'OrderController@setStatus');
        Route::post('orders/cancelOrder', 'OrderController@cancelOrder')->name('cancel-order');
    });
});
