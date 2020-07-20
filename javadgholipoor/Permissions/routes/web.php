<?php

Route::group(['as' => 'admin.', 'prefix' => 'admin', 'middleware' => ['auth:web', 'permission'], 'namespace' => 'LaraBase\Permissions\Controllers'], function () {
    
    Route::get('permissions/sync', 'PermissionController@sync')->name('permissions.sync');
    Route::resource('permissions', 'PermissionController');
    
});
