<?php
Route::group(['prefix' => 'options'], function () {
    Route::get('themes', 'OptionController@themes')->name('options.themes');
});
