<?php

namespace LaraBase\Permissions;

use LaraBase\Call;

class Permissions implements Call {
    
    public function __call($method, $arguments) {
        return $this->manager()->$method(...$arguments);
    }
    
    public static function __callStatic($method, $arguments) {
        return (new Permissions())->manager()->$method(...$arguments);
    }
    
    public function manager() {
        return new Manager();
    }
    
}
