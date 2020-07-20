<?php

Route::group(['prefix' => '', 'namespace' => 'LaraBase\Wallet\Controllers'], function () {

    Route::group(['prefix' => 'wallet', 'as' => 'wallet'], function () {
        Route::middleware('auth:web')->post('charge', 'WalletController@charge');
    });

});

Route::group(['as' => 'admin.', 'prefix' => 'admin', 'middleware' => 'auth:web', 'namespace' => 'LaraBase\Wallet\Controllers'], function () {

});
