<?php
Route::group(['prefix' => 'v1'], function () {
    Route::resource('roles', 'RoleController');
});
