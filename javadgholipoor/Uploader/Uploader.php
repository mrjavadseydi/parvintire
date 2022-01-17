<?php

namespace LaraBase\Uploader;

use LaraBase\Call;

class Uploader implements Call {
    
    public function __call($method, $arguments) {
        return $this->manager()->$method(...$arguments);
    }
    
    public static function __callStatic($method, $arguments) {
        return (new Options)->manager()->$method(...$arguments);
    }
    
    public function manager() {
        return new Manager();
    }
    
}
