<?php

Route::group(['as' => 'admin.', 'prefix' => 'admin', 'middleware' => 'auth:web', 'namespace' => 'LaraBase\Attributes\Controllers'], function () {

    Route::get('attributes/search', 'AttributeController@search')->name('attributes.search');
    Route::post('attributes/search/update', 'AttributeController@searchUpdate')->name('attributes.search.update');

    Route::get('attributes/{id}/destroy/confirm', 'AttributeController@destroyConfirm')->name('attributes.destroy.confirm');
    Route::get('attribute-keys/{id}/destroy/confirm', 'AttributeKeyController@destroyConfirm')->name('attribute-keys.destroy.confirm');
    Route::get('attribute-values/{id}/destroy/confirm', 'AttributeValueController@destroyConfirm')->name('attribute-values.destroy.confirm');

    Route::get('attributes/translate', 'AttributeController@translate');
    Route::post('attributes/translate/store', 'AttributeController@storeTranslate');

    Route::get('attribute-keys/translate', 'AttributeKeyController@translate');
    Route::post('attribute-keys/translate/store', 'AttributeKeyController@storeTranslate');

    Route::get('attribute-values/translate', 'AttributeValueController@translate');
    Route::post('attribute-values/translate/store', 'AttributeValueController@storeTranslate');

    Route::resources([
        'attributes' => 'AttributeController',
        'attribute-keys' => 'AttributeKeyController',
        'attribute-values' => 'AttributeValueController'
    ]);

});
