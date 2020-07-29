<?php

use LaraBase\Helpers\Converter;

if(!function_exists('toEnglish')){
    function toEnglish($string){
        return Converter::toEnglish($string);
    }
}

if(!function_exists('toPersian')){
    function toPersian($string){
        return Converter::toPersian($string);
    }
}

if(!function_exists('toArabic')){
    function toArabic($string){
        return Converter::toArabic($string);
    }
}

if(!function_exists('convertDistance')){
    function convertDistance($meter, $lang = 'fa'){
        return Converter::distance($meter, $lang);
    }
}

if(!function_exists('convertSecondToTime')){
    function convertSecondToTime($seconds){
        return Converter::secondToTime($seconds);
    }
}
