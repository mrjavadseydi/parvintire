<?php

Route::group(['prefix' => 'api', 'middleware' => 'api'], function () {

    Route::group(['prefix' => 'attributes', 'namespace' => 'LaraBase\Attributes\Controllers'], function () {

        Route::get('search/keys', 'AttributeController@searchKeys')->name('searchKeys');
        Route::get('search/values', 'AttributeController@searchValues')->name('searchValues');

        Route::get('keys/{id}', 'AttributeController@keys')->name('keys');
        Route::get('values/{id}', 'AttributeController@values')->name('values');

        Route::get('survey', 'AttributeController@survey')->name('survey');

    });

});
