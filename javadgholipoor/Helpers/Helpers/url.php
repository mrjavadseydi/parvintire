<?php

use LaraBase\Helpers\Url;

if(!function_exists('fileSize')){
    function fileSize($url, $useHead = true){
        return Url::fileSize($url, $useHead);
    }
}

if(!function_exists('current')){
    function current($removeQueryString = false){
        return Url::current($removeQueryString);
    }
}

if(!function_exists('mainUrl')){
    function mainUrl($url){
        return Url::main($url);
    }
}
