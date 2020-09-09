<?php

use LaraBase\Auth\Models\User;

function getUser($userId) {
    $userKey = 'user_' . $userId;
    if (!hasCache($userKey)) {
        $user = User::whereId($userId)->first();
        setCache($userKey, $user, 9999);
    }
    return $user;
}

function hasAuthReferer() {
    if (hasCache(ip().'Referer'))
        return true;

    return false;
}

function setAuthReferer() {
    setCache(ip().'Referer', url()->full());
}

function getAuthReferer() {
    $key = ip().'Referer';
    $referer = getCache($key);
    deleteCache($referer);
    return $referer;
}
