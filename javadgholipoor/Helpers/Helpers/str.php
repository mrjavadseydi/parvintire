<?php

use LaraBase\Helpers\Str;

if(!function_exists('slug')){
    function slug($sting, $separator = '-'){
        return Str::slug($sting, $separator);
    }
}

if(!function_exists('randomString')){
    function randomString($length = 32){
        return Str::randomString($length);
    }
}

if(!function_exists('generateUniqueToken')){
    function generateUniqueToken(){
        return Str::generateUniqueToken();
    }
}

if(!function_exists('generateToken')){
    function generateToken($length = 32) {
        return Str::generateToken($length);
    }
}

if(!function_exists('generateInt')){
    function generateInt($length = 32) {
        return Str::generateInt($length);
    }
}

if(!function_exists('removeExtension')){
    function removeExtension($name, $mod = '_') {
        return Str::removeExtension($name, $mod);
    }
}

if(!function_exists('echoAttributes')){
    function echoAttributes($attributes) {
        return Str::echoAttributes($attributes);
    }
}
