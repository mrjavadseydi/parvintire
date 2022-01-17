<?php

namespace LaraBase\Attachments;

use LaraBase\Attachments\Models\Attachment;

class Attachments {
    
    public function __call($method, $arguments) {
        return $this->manager()->$method(...$arguments);
    }
    
    public static function __callStatic($method, $arguments) {
        return (new Attachment())->manager()->$method(...$arguments);
    }
    
    public function manager() {
        return new Attachment();
    }
    
}
