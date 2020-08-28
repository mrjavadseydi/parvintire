<?php
Route::group(['prefix' => 'v1'], function() {
    Route::post('upload', 'UploadController@upload');
});
