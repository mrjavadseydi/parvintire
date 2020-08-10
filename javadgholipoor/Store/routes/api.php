<?php
Route::group(['prefix' => 'product'], function () {
    Route::get('search', 'ProductController@search');
});
