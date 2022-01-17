<?php
Route::post('like', 'PostController@like')->name('posts.like');
Route::post('rate', 'PostController@rate')->name('posts.rate');
Route::post('favorite', 'PostController@favorite')->middleware('auth:web')->name('posts.favorite');
Route::get('search', 'PostController@search')->name('search');

Route::get('p/{id}', function ($id) {
    $post = \LaraBase\Posts\Models\Post::find($id);
    if ($post != null)
        return redirect($post->href(), 301);
});
