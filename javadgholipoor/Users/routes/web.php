<?php

Route::group(['prefix' => '', 'namespace' => 'LaraBase\Users\Controllers'], function () {

    Route::post('updateAvatar', 'UserController@updateAvatar')->name('updateAvatar');
    
    Route::group(['prefix' => 'switch', 'middleware' => 'auth:web'], function () {
        Route::get('user/{switchTo}', 'UserController@switch');
    });
    
});

Route::group(['as' => 'admin.', 'prefix' => 'admin', 'middleware' => 'auth:web', 'namespace' => 'LaraBase\Users\Controllers'], function () {

//    Route::any('dynamic/{type}', 'DynamicController@dynamic');
    Route::get('users/verify/{type}/{id}', 'UserController@verify')->name('users.verify');
    Route::get('users/{id}/destroy/confirm', 'UserController@destroyConfirm')->name('users.destroy.confirm');
    Route::resource('users', 'UserController');
    
});
