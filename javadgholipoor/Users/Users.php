<?php

namespace LaraBase\Users;

use LaraBase\Auth\Models\User;
use LaraBase\Call;

class Users implements Call {
    
    public function __call($method, $arguments) {
        return $this->manager()->$method(...$arguments);
    }
    
    public static function __callStatic($method, $arguments) {
        return (new Users)->manager()->$method(...$arguments);
    }
    
    public function manager() {
        return new User();
    }
    
}
