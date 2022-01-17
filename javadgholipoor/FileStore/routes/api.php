<?php
Route::group(['middleware' => 'api:auth'], function () {
    Route::group(['prefix' => 'v1'], function () {
        Route::group(['prefix' => 'files'], function () {
            Route::post('/', 'FileController@files')->name('files');
            Route::post('get-file', 'FileController@get')->name('getFile');
            Route::post('add-file', 'FileController@add')->name('addFile');
            Route::post('delete-file', 'FileController@delete')->name('deleteFile');
            Route::post('sort-files', 'FileController@sort')->name('sortFiles');
            Route::post('get-group', 'GroupController@get')->name('getFileGroup');
            Route::post('add-group', 'GroupController@add')->name('addFileGroup');
            Route::post('update-group', 'GroupController@update')->name('updateFileGroup');
            Route::post('delete-group', 'GroupController@delete')->name('deleteFileGroup');
            Route::post('sort-groups', 'GroupController@sort')->name('sortGroups');
        });
        Route::post('payment/course', 'PaymentController@payment')->name('paymentCourse');
    });
});
