<?php
Route::group(['middleware' => 'permission'], function () {
    Route::get('permissions/sync', 'PermissionController@sync')->name('permissions.sync');
    Route::resource('permissions', 'PermissionController');
});
