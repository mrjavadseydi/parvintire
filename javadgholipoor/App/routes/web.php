<?php

Route::group(['as' => 'admin.', 'prefix' => 'admin', 'namespace'  => 'LaraBase', 'middleware' => 'auth:web'], function () {

    Route::post('delete', 'CoreController@delete')->name('delete');

});

Route::group(['prefix' => 'profile', 'middleware' => 'auth:web', 'namespace' => 'LaraBase\App\Controllers'], function () {
    Route::get('', 'ProfileController@profile')->name('profile');
    Route::post('update', 'ProfileController@update')->name('profile.update');
    Route::get('password', 'ProfileController@password')->name('profile.password');
    Route::post('password/update', 'ProfileController@updatePassword')->name('profile.password.update');
    Route::get('orders', 'ProfileController@orders')->name('profile.orders');
    Route::get('orders/{id}', 'ProfileController@order')->name('profile.order');
    Route::get('favorites', 'ProfileController@favorites')->name('profile.favorites');
});

Route::group(['prefix' => 'language', 'namespace' => 'LaraBase\App\Controllers'], function () {

    Route::get('set/{lang}', 'LanguageController@set')->name('language.set');
    Route::get('get', 'LanguageController@get')->name('language.get');

});

Route::group(['namespace'  => 'LaraBase\App\Controllers'], function () {

    Route::get('faq', 'PageController@faq')->name('faq');

});

Route::group(['namespace'  => 'LaraBase'], function () {

    Route::get('down', 'CoreController@down')->name('siteDown');
    Route::get('up', 'CoreController@up')->name('siteUp');

});
