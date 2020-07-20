<?php

Route::group(['namespace' => 'Project\DigiShop\Controllers'], function () {

    Route::get('/', 'PageController@home')->name('home');
    Route::get('products/{id}/{slug}', 'PageController@product')->name('product');
    Route::get('articles/{id}/{slug}', 'PageController@article')->name('article');
    Route::get('contact-us', 'PageController@contactUs')->name('contact-us');

});
