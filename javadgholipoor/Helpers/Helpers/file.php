<?php

use LaraBase\Helpers\File;

if(!function_exists('byteFormat')){
    function byteFormat( $bytes, $precision = 2, $lang = 'fa' ){
        return File::byteFormat( $bytes, $precision, $lang );
    }
}

if(!function_exists('getStringExtension')){
    function getStringExtension( $string, $byDot = false ){
        return File::getStringExtension($string, $byDot);
    }
}

if(!function_exists('downloadFile')){
    function downloadFile( $url, $pathFile, $headers = [] ){
        return File::downloadFile($url, $pathFile, $headers);
    }
}

if(!function_exists('extractZip')){
    function extractZip( $file, $extractTo ){
        return File::extractZip( $file, $extractTo );
    }
}

if(!function_exists('curlPostFields')){
    /**
     * For safe multipart POST request for PHP5.3 ~ PHP 5.4.
     *
     * @param resource $cUrl cURL resource
     * @param array $params "name => value"
     * @param array $files "name => path"
     * @return bool
     */
    function curlPostFields( $cUrl, array $params = array(), array $files = array() ){
        return File::curlPostFields( $cUrl, $params, $files );
    }
}

if(!function_exists('uploadFiles')){
    function uploadFiles( $url, $files, $params = [] ){
        return File::uploadFiles( $url, $files, $params );
    }
}

if(!function_exists('removeDir')){
    function removeDir( $dir ){
        return File::removeDir( $dir );
    }
}
