<?php
Route::group(['prefix' => 'v1'], function () {
    Route::get('roles', 'RoleController@roles');
});
