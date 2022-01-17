<?php
Route::group(['middleware' => 'api:auth'], function () {
    Route::group(['prefix' => 'v1'], function () {
        Route::get('permissions/sync', 'PermissionController@sync');
        Route::resource('permissions', 'PermissionController');
    });
});
