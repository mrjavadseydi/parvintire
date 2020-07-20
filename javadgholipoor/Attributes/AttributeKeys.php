<?php

namespace LaraBase\Attributes;

use LaraBase\Attributes\Models\AttributeKey;

class AttributeKeys {
    
    public function __call($method, $arguments) {
        return $this->manager()->$method(...$arguments);
    }
    
    public static function __callStatic($method, $arguments) {
        return (new AttributeKey())->manager()->$method(...$arguments);
    }
    
    public function manager() {
        return new AttributeKey();
    }
    
}
