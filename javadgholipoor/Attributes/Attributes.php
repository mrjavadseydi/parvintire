<?php

namespace LaraBase\Attributes;

use LaraBase\Attributes\Models\Attribute;

class Attributes {
    
    public function __call($method, $arguments) {
        return $this->manager()->$method(...$arguments);
    }
    
    public static function __callStatic($method, $arguments) {
        return (new Attribute())->manager()->$method(...$arguments);
    }
    
    public function manager() {
        return new Attribute();
    }
    
}
