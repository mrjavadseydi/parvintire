<?php
Route::get('cart', 'CartController@cart')->name('cart');
Route::get('cart/address', 'CartController@address')->name('cart.address');
Route::get('order/change/{orderId}', 'OrderController@changeOrder')->name('order.change');
Route::post('order/address', 'OrderController@address')->name('order.address');
Route::get('cart/payment', 'CartController@payment')->name('cart.payment');
Route::post('order/payment', 'OrderController@payment')->name('order.payment');
Route::get('order/verify', 'OrderController@paymentVerify')->name('order.verify');
Route::post('addToCart', 'OrderController@addToCart')->name('addToCart');
Route::post('deleteFromCart', 'OrderController@deleteFromCart')->name('deleteFromCart');
Route::post('getProduct', 'OrderController@getProduct')->name('getProduct');
