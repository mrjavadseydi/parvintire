<?php
Route::group(['middleware' => 'api:auth'], function () {
    Route::group(['prefix' => 'v1'], function () {
        Route::resource('roles', 'RoleController');
    });
});
