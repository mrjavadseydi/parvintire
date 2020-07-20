<?php

Route::group(['as' => 'admin.', 'prefix' => 'admin', 'middleware' => ['auth:web','roles'], 'namespace' => 'LaraBase\Roles\Controllers'], function () {
    
    
    //    Route::get('roles/sync', function () {
    //        can('ok');
    //    })->name('roles.sync');
    Route::resource('roles', 'RoleController');
    
});
