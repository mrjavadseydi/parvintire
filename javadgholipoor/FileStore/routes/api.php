<?php
Route::group([], function () {
    Route::group(['prefix' => 'v1'], function () {

        Route::group(['prefix' => 'files'], function () {

            Route::post('/', 'FileController@files')->middleware('can:files')->name('files');
            Route::post('get-file', 'FileController@get')->middleware('can:getFile')->name('getFile');
            Route::post('add-file', 'FileController@add')->middleware('can:addFile')->name('addFile');
            Route::post('delete-file', 'FileController@delete')->middleware('can:deleteFile')->name('deleteFile');
            Route::post('sort-files', 'FileController@sort')->middleware('can:sortFiles')->name('sortFiles');

            Route::post('get-group', 'GroupController@get')->middleware('can:getFileGroup')->name('getFileGroup');
            Route::post('add-group', 'GroupController@add')->middleware('can:addFileGroup')->name('addFileGroup');
            Route::post('update-group', 'GroupController@update')->middleware('can:updateFileGroup')->name('updateFileGroup');
            Route::post('delete-group', 'GroupController@delete')->middleware('can:deleteFileGroup')->name('deleteFileGroup');
            Route::post('sort-groups', 'GroupController@sort')->middleware('can:sortGroups')->name('sortGroups');

        });

        Route::post('payment/course', 'PaymentController@payment')->name('paymentCourse');

    });
});

