<?php

/**
 * @return \LaraBase\Hook
 */
function hook(){
    return new \LaraBase\Hook\Hook();
}

/**
 * @param $name
 * @param $callback
 * @param int $priority
 * @param int $acceptedArgs
 * @return bool
 */
function addFilter($name, $callback, $priority = 10, $acceptedArgs = 1){
    return hook()->add($name, $callback, $priority, $acceptedArgs);
}

/**
 * @param $name
 * @param null $value
 * @return array|mixed|object|string|null
 */
function applyFilter($name, $value = null){
    return hook()->do($name, $value);
}

/**
 * @param $name
 */
function removeFilter($name){
    return hook()->remove($name);
}

/**
 * @param $name
 * @return mixed
 */
function hasFilter($name){
    return hook()->has($name);
}

/**
 * @param $name
 * @param $callback
 * @param int $priority
 * @param int $acceptedArgs
 * @return bool
 */
function addAction($name, $callback, $priority = 10, $acceptedArgs = 1){
    return addFilter($name, $callback, $priority, $acceptedArgs);
}

/**
 * @param $name
 * @param null $value
 * @return array|mixed|object|string|null
 */
function doAction($name, $value = null){
    return applyFilter($name, $value);
}

/**
 * @param $name
 * @return array|mixed|object|string|null
 */
function removeAction($name){
    return removeFilter($name);
}

/**
 * @param $name
 * @return mixed
 */
function hasAction($name){
    return hasFilter($name);
}
