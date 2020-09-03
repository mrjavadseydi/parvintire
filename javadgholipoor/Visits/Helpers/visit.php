<?php

use \LaraBase\Visits\Models\Visit;
use Illuminate\Support\Facades\Cache;

function addVisit($relation = null, $relationId = null) {

    $url = url()->current();
    $ip  = ip();

    $data = [
        'url'   => $url,
        'ip'    => $ip,
    ];

    $cacheKey = "visit_" . md5($url . $ip);
    if (hasCache($cacheKey)) {
        $count = getCache($cacheKey) + 1;
    } else {
        $old = Visit::where($data)->first();
        if ($old == null) {
            $count = 1;
        } else {
            $count = $old->count + 1;
        }
    }

    if (auth()->check()) {
        $data['user_id'] = auth()->id();
    }

    if (isset($relation)) {
        $data['relation'] = $relation;
    } else {
        $data['relation'] = Route::currentRouteName();
    }

    if (isset($relationId)) {
        $data['relation_id'] = $relationId;
    }

    if ($count == 1) {
        $data['count'] = 1;
        Visit::create($data);
        setCache($cacheKey, 1, 60);
    } else {
        $data['count'] = $count;
        updateCache($cacheKey, $count, 60);
        Visit::where([
            'url'   => $url,
            'ip'    => $ip,
        ])->update(['count' => $count]);
    }

}

function getPostVisit($postId) {
    $cacheKey = "postVisit{$postId}";
    if (!Cache::has($cacheKey)) {
        $visits = Visit::where([
            'relation' => 'post',
            'relation_id' => $postId
        ])->sum('count');
        Cache::put($cacheKey, $visits, 720);
    }
    return Cache::get($cacheKey);
}
