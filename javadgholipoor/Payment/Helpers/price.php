<?php

function convertMoney()
{
    return [
        'IRR-TOMAN' => 0.1,
        'TOMAN-IRR' => 10
    ];
}

function convertPrice($price, $currency = 'IRR') {

    $convert = convertMoney();
    $siteCurrency = siteCurrency();

    if ($siteCurrency == $currency)
        return $price;

    $currencyIndex = $currency."-".$siteCurrency;
    return $price * $convert[$currencyIndex];

}

function toIRR($price) {

    $convert = convertMoney();
    $siteCurrency = siteCurrency();

    if ($siteCurrency == 'IRR')
        return $price;

    $currencyIndex = $siteCurrency."-IRR";
    return $price * $convert[$currencyIndex];

}

function toToman() {

}

function toEUR() {

}

function toXCD() {

}
