<?php

Route::group(['prefix' => 'download', 'middleware' => 'auth:web', 'namespace' => 'LaraBase\Download\Controllers'], function () {

    Route::get('download/{id}/{title}', 'DownloadController@download')->name('download');
    Route::get('attachment/{id}', 'DownloadController@attachment')->name('attachmentDownload');
    Route::get('video/{id}', 'DownloadController@video')->name('videoDownload');

});

Route::group(['prefix' => 'download', 'namespace' => 'LaraBase\Download\Controllers'], function () {
    Route::get('file/{id}', 'DownloadController@file')->name('file');
});

