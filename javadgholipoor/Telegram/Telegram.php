<?php

namespace LaraBase\Telegram;

use LaraBase\Call;

class Telegram implements Call {

    public static function isActive() {
        return config('telegramConfig.active');
    }

    public function __call($method, $arguments) {
        return $this->manager()->$method(...$arguments);
    }

    public static function __callStatic($method, $arguments) {
        return self::manager()->$method(...$arguments);
    }

    public function manager() {
        return new Manager();
    }

}
