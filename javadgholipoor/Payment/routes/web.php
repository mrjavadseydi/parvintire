<?php

Route::group([
    'prefix'     => 'payment',
//    'middleware' => 'auth:web',
    'namespace'  => 'LaraBase\Payment\Controllers'
], function () {
    
    Route::any('request/{id}/{token}', 'PaymentController@request')->name('paymentRequest');
    Route::any('verify', 'PaymentController@verify')->name('paymentVerify');
    
});
