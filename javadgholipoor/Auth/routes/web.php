<?php

Route::get('register', 'RegisterController@index')->name('register');
Route::post('register', 'RegisterController@register')->name('register');

Route::get('login', 'LoginController@index')->name('login');
Route::post('login', 'LoginController@login')->name('login');

Route::get('verify', 'VerifyController@index')->name('verify');
Route::post('verify-mobile', 'VerifyController@verifyMobile')->name('verifyMobile');

Route::get('google/sign', 'GoogleController@sign')->name('googleSign');
Route::get('google/callback', 'GoogleController@callback')->name('googleCallBack');

Route::group(['prefix' => 'password'], function () {
    Route::get('recovery', 'PasswordController@index')->name('passwordRecovery');
    Route::post('recovery', 'PasswordController@recovery')->name('passwordRecovery');
    Route::get('send', 'PasswordController@send')->name('passwordSend');
    Route::post('mobile', 'PasswordController@mobile')->name('passwordMobile');
    Route::get('change/{rememberToken}', 'PasswordController@change')->name('passwordChange');
    Route::post('change/{rememberToken}', 'PasswordController@Update')->name('passwordChange');
});

Route::get('logout', function () {
    auth()->logout();
    return redirect('login?signout');
})->name('logout');
