<?php

namespace LaraBase\Attributes;

use LaraBase\Attributes\Models\AttributeValue;

class AttributeValues {
    
    public function __call($method, $arguments) {
        return $this->manager()->$method(...$arguments);
    }
    
    public static function __callStatic($method, $arguments) {
        return (new AttributeValue())->manager()->$method(...$arguments);
    }
    
    public function manager() {
        return new AttributeValue();
    }
    
}
