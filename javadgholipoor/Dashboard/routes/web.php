<?php

Route::group(['as' => 'admin.', 'prefix' => 'admin', 'middleware' => 'auth:web', 'namespace' => 'LaraBase\Dashboard\Controllers'], function () {
    Route::get('', 'DashboardController@index')->name('dashboard');
});
