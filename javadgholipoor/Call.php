<?php

namespace LaraBase;

interface Call {
    
    public function __call($method, $arguments);
    
    public static function __callStatic($method, $arguments);
    
    public function manager();
    
}
