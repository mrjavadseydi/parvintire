<?php

namespace LaraBase\Posts;

use LaraBase\Call;
use LaraBase\Posts\Models\Post;

class Posts implements Call {
    
    public function __call($method, $arguments) {
        return $this->manager()->$method(...$arguments);
    }
    
    public static function __callStatic($method, $arguments) {
        return (new Posts())->manager()->$method(...$arguments);
    }
    
    public function manager() {
        return new Post();
    }
    
}
