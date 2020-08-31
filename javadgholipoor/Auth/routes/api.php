<?php
Route::group(['prefix' => 'v1'], function () {
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
    Route::post('auth/verify/{token}', 'AuthController@verify');
});

