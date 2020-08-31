<?php
Route::group(['prefix' => 'v1'], function () {
    Route::group(['middleware' => 'api:auth'], function () {

    });
});
