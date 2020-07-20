<?php

namespace LaraBase\Roles;

use LaraBase\Call;
use LaraBase\Roles\Models\Role;

class Roles implements Call {
    
    public function __call($method, $arguments) {
        return $this->manager()->$method(...$arguments);
    }
    
    public static function __callStatic($method, $arguments) {
        return (new Roles)->manager()->$method(...$arguments);
    }
    
    public function manager() {
        return new Role();
    }
    
}
