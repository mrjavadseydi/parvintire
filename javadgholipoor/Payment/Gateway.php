<?php


namespace LaraBase\Payment;


use LaraBase\Call;

class Gateway implements Call {
    
    public function __call($method, $arguments) {
        return $this->manager()->$method(...$arguments);
    }
    
    public static function __callStatic($method, $arguments) {
        return (new Manager)->manager()->$method(...$arguments);
    }
    
    public function manager($gateway = 'ZarinPal') {
        return new Manager($gateway);
    }
    
}
