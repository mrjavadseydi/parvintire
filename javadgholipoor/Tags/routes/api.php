<?php
Route::group(['prefix' => 'tags'], function () {
    Route::get('search', 'TagController@search');
});
