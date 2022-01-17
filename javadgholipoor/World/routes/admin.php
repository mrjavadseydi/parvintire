<?php
Route::group(['prefix' => 'world'], function () {
    Route::get('sql', 'WorldController@sql');
});
