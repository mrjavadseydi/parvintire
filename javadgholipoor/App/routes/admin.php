<?php
Route::view('error', includeAdmin('master'))->middleware('can:administrator')->name('error');
Route::get('sidebar', 'SidebarController@sidebar')->middleware('can:administrator')->name('sidebar');
Route::post('delete', 'CoreController@delete')->name('delete');
