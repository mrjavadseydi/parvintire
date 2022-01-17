<?php

use LaraBase\Helpers\MobileDetect;

function isMobile()
{
    $mobileDetected = new MobileDetect();

    if ($mobileDetected->isMobile())
        return true;

    return false;
}

function getUserAgent() {
    $mobileDetected = new MobileDetect();
    return $mobileDetected->getUserAgent();
}
