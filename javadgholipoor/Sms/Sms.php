<?php

namespace LaraBase\Sms;

use LaraBase\Call;

class Sms implements Call {
    
    public function __call($method, $arguments) {
        return $this->manager()->$method(...$arguments);
    }
    
    public static function __callStatic($method, $arguments) {
        return self::manager()->$method(...$arguments);
    }
    
    public function manager() {
        return new Manager();
    }
    
}
