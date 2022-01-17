<?php

function gateway($gateway = 'ZarinPal') {
    $class = new \LaraBase\Payment\Gateway();
    return $class->manager($gateway);
}

function siteGateway()
{
    return getOption('gateway');
}
