<?php

Route::group(['prefix' => 'profile', 'middleware' => 'auth:web'], function () {
    Route::get('', 'ProfileController@profile')->name('profile');
    Route::post('update', 'ProfileController@update')->name('profile.update');
    Route::get('password', 'ProfileController@password')->name('profile.password');
    Route::post('password/update', 'ProfileController@updatePassword')->name('profile.password.update');
    Route::get('orders', 'ProfileController@orders')->name('profile.orders');
    Route::get('orders/{id}', 'ProfileController@order')->name('profile.order');
    Route::get('favorites', 'ProfileController@favorites')->name('profile.favorites');
});

Route::group(['prefix' => 'language'], function () {
    Route::get('set/{lang}', 'LanguageController@set')->name('language.set');
    Route::get('get', 'LanguageController@get')->name('language.get');
});

Route::get('faq', 'PageController@faq')->name('faq');
Route::get('categories', 'PageController@categories')->name('categories');
Route::get('categories/{id}/{slug}', 'PageController@category')->name('category');
foreach (['article', 'product', 'book'] as $type) {
//        Route::get($type . 's/{id}/{slug}', 'PageController@singlePage')->name("{$type}");
//        Route::post($type . 's/{id}/{slug}', 'PageController@singlePage')->name("{$type}");
}

Route::get('sidebar', 'SidebarController@sidebar')->middleware('can:administrator')->name('sidebar');
Route::get('down', 'CoreController@down')->name('siteDown');
Route::get('up', 'CoreController@up')->name('siteUp');
