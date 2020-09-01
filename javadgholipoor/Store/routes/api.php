<?php
Route::group(['middleware' => 'api:auth'], function () {
    Route::group(['prefix' => 'v1'], function () {
        Route::group(['prefix' => 'product'], function () {
            Route::get('search', 'ProductController@search');
        });
        Route::post('orders/setStatus', 'OrderController@setStatus');
    });
});
