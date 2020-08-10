<?php

Route::group(['prefix' => 'upload'], function () {

    Route::post('', 'UploadController@upload')->name('upload');
    Route::post('delete/{attachmentId}', 'UploadController@delete')->name('delete');
    Route::get('files', 'UploadController@files')->name('uploadFiles');
    Route::post('image-cropper', 'UploadController@imageCropper')->name('image-cropper');

});

Route::group(['prefix' => 'render', 'middleware' => 'auth:web'], function () {

    Route::get('image/{id}/{width}/{height}', 'UploadController@renderImage')->name('renderImage');

});
