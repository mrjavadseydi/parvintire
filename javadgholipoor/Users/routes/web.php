<?php
Route::post('updateAvatar', 'UserController@updateAvatar')->name('updateAvatar');

Route::group(['prefix' => 'switch', 'middleware' => 'auth:web'], function () {
    Route::get('user/{switchTo}', 'UserController@switch');
});
