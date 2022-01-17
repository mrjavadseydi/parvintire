<?php
Route::group(['middleware' => 'roles'], function () {
    Route::resource('roles', 'RoleController');
});
