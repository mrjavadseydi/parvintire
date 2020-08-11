<?php
Route::get('/', 'PageController@home')->name('home');
Route::get('products/{id}/{slug}', 'PageController@product')->name('product');
Route::get('articles/{id}/{slug}', 'PageController@article')->name('article');
Route::get('podcasts/{id}/{slug}', 'PageController@podcast')->name('podcast');
Route::get('books/{id}/{slug}', 'PageController@book')->name('book');
Route::get('contact-us', 'PageController@contactUs')->name('contact-us');
