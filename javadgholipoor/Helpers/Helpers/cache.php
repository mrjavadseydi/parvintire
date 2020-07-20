<?php

use Illuminate\Support\Facades\Cache;

function hasCache($key) {
    return Cache::has($key);
}

function setCache($key, $value, $minutes = 'forever') {
    if ($minutes == 'forever') {
        Cache::forever($key, $value);
    } else {
        Cache::add($key, $value, ($minutes*60));
    }
}

function updateCache($key, $value, $minutes) {
    deleteCache($key);
    setCache($key, $value, $minutes);
}

function getCache($key) {
    if (hasCache($key))
        return Cache::get($key);

    return null;
}

function deleteCache($key) {
    if (hasCache($key))
        Cache::delete($key);
}
