<?php

use LaraBase\Helpers\Cookie;

function hasCookie($key) {
    return Cookie::has($key);
}

function setCookiee($key, $value, $minutes = 0, $path = '/', $domain = '', $secure = '', $httpOnly = 1) {
    return Cookie::set($key, $value, $minutes, $path, $domain, $secure, $httpOnly);
}

function setCookieForever($key, $value) {
    return Cookie::set($key, $value, 'forever');
}

function getCookie($key) {
    return Cookie::get($key);
}

function deleteCookie($key) {
    return Cookie::delete($key);
}
