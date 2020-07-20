<?php

use LaraBase\Posts\Models\PostType;
use Illuminate\Support\Facades\Cache;

function posts() {
    $posts = new \LaraBase\Posts\Posts();
    return $posts->manager();
}

function getPostTypes() {
    $cacheKey = env('APP_NAME') . 'postTypes';
    if (!Cache::has($cacheKey)) {
        $get = PostType::all();
        if ($get != null) {
            Cache::forever($cacheKey, $get);
        } else {
            return [];
        }
    }
    return Cache::get($cacheKey);
}

function getPostType($type) {
    $cacheKey = env('APP_NAME') . "postType_{$type}";
    if (!Cache::has($cacheKey)) {
        $get = PostType::where('type', $type)->first();
        if ($get != null) {
            Cache::forever($cacheKey, $get);
        } else {
            return null;
        }
    }
    return Cache::get($cacheKey);
}

function getPostTypeId($id) {
    $cacheKey = env('APP_NAME') . "postTypeId_{$id}";
    if (!Cache::has($cacheKey)) {
        $get = PostType::where('id', $id)->first();
        if ($get != null) {
            Cache::forever($cacheKey, $get);
        } else {
            return null;
        }
    }
    return Cache::get($cacheKey);
}

function initPost($post) {
    addVisit('post', $post->id);

    if ($post == null)
        return  abort(404);

    if ($post->status != 'publish')
        return abort(404);
}
