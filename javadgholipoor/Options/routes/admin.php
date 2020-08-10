<?php
Route::group(['prefix' => 'options'], function () {
    Route::get('general', 'OptionController@general')->name('options.general');
    Route::get('images', 'OptionController@images')->name('options.images');
    Route::get('themeValues', 'OptionController@themeValues')->name('options.themeValues');
    Route::get('protocol', 'OptionController@protocol')->name('options.protocol');
    Route::get('language', 'OptionController@language')->name('options.language');
    Route::post('update', 'OptionController@update')->name('options.update');
    Route::get('check-for-updates', 'OptionController@checkForUpdates')->name('options.check-for-updates');
});
