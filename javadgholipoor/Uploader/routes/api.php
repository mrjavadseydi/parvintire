<?php
Route::group(['middleware' => 'auth:api'], function () {
    Route::group(['prefix' => 'v1'], function() {
        Route::post('upload', 'UploadController@upload');
    });
});
