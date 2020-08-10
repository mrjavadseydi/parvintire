<?php
Route::group(['prefix' => 'payment'], function () {
    Route::any('request/{id}/{token}', 'PaymentController@request')->name('paymentRequest');
    Route::any('verify', 'PaymentController@verify')->name('paymentVerify');
});
