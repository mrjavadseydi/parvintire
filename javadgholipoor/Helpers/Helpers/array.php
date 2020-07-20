<?php

use LaraBase\Helpers\Arr;

if(!function_exists('isJson')){
    function isJson($string){
        return Arr::isJson($string);
    }
}

if(!function_exists('isSerialize')){
    function isSerialize($string){
        return Arr::isSerialize($string);
    }
}

if(!function_exists('treeView')){
    function treeView($records, $options = []){
        return Arr::treeView($records, $options);
    }
}

if(!function_exists('selectOptions')){
    function selectOptions($selected, $records, $options = []){
        return Arr::selectOptions($selected, $records, $options);
    }
}

if(!function_exists('checkbox')){
    function checkbox($selected, $records, $name = 'categories', $options = []){
        return Arr::checkbox($selected, $records, $name, $options);
    }
}

