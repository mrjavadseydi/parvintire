<?php

use LaraBase\Sms\Sms;

function sms() {
    $sms = new Sms();
    return $sms->manager();
}
