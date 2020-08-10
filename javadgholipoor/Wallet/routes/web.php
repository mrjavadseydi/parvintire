<?php
Route::group(['prefix' => 'wallet', 'as' => 'wallet'], function () {
    Route::middleware('auth:web')->post('charge', 'WalletController@charge');
});
