<?php

use LaraBase\Telegram\Telegram;

function telegram() {
    $class = new Telegram();
    return $class->manager();
}

function isActiveTelegram() {
    return Telegram::isActive();
}
