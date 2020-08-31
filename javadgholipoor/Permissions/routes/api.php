<?php
Route::group(['middleware' => 'auth:api'], function () {
    Route::group(['prefix' => 'v1'], function () {
        Route::get('permissions/sync', 'PermissionController@sync');
        Route::resource('permissions', 'PermissionController');
    });
});
