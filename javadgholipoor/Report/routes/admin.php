<?php

Route::group(['middleware' => 'can:reports'], function () {

    Route::group(['prefix' => 'reports', 'as' => 'reports.'], function () {
        Route::get('search', 'SearchController@search')->middleware('can:searchReport')->name('search');
        Route::get('search/check/{keyword}/{check}', 'SearchController@searchCheck')->middleware('can:searchReport')->name('search.check');
    });

});
