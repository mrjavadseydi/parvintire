<?php

Route::group([
    'as'         => 'admin.',
    'prefix'     => 'admin',
    'middleware' => 'auth:web',
    'namespace'  => 'LaraBase\Posts\Controllers'
], function () {

    Route::get('post-types/{id}/destroy/confirm', 'PostTypeController@destroyConfirm')->name('post-types.destroy.confirm');
    Route::get('posts/translate', 'PostController@translate');
    Route::post('posts/translate/store', 'PostController@storeTranslate');
    Route::resource('posts', 'PostController');
    Route::resource('post-types', 'PostTypeController');

});

Route::group([
    'namespace'  => 'LaraBase\Posts\Controllers'
], function () {

    Route::post('like', 'PostController@like')->name('posts.like');
    Route::post('rate', 'PostController@rate')->name('posts.rate');
    Route::post('favorite', 'PostController@favorite')->middleware('auth:web')->name('posts.favorite');
    Route::get('search', 'PostController@search')->name('search');

    Route::get('p/{id}', function ($id) {
        $post = \LaraBase\Posts\Models\Post::find($id);
        if ($post != null)
            return redirect($post->href(), 301);
    });

});
