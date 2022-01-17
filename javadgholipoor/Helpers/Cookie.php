<?php

namespace LaraBase\Helpers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Cookie {

    public static function has($key) {
        if (isset($_COOKIE[$key]))
            return true;

        return false;
    }

    public static function set($key, $value, $minutes = 0, $path = '/', $domain = '', $secure = '', $httpOnly = 1) {

        $time = 0;
        if ($minutes == 'forever') {
            $time = time() + 2592000;
        } else if ($minutes == 'delete') {
            $time = time() - 3600;
        } else if ($minutes != 0){
            $time = time() + ($minutes * 60);
        }

        setcookie($key, $value, $time, $path, $domain, $secure, $httpOnly);
    }

    public static function get($key) {
        if (isset($_COOKIE[$key]))
            return $_COOKIE[$key];

        return null;
    }

    public static function delete($key) {
        if (isset($_COOKIE[$key])) {
            setCookiee($key, '', 'delete');
        }
    }

}
