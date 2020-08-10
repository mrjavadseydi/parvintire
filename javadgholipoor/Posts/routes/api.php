<?php
Route::group(['prefix' => 'post'], function () {

    Route::get('{id}', function ($id) {
        $post = \LaraBase\Posts\Models\Post::find($id);
        $tags['tags'] = [
            'tag1', 'tag2', 'tag3'
        ];
//            $output = array_merge($post->toArray(), ->toArray());
        return array_merge($post->toArray(), $tags);
    });

});
