<?php

use LaraBase\Helpers\Google;

if (!function_exists('googleReCaptchaV3')) {

    function googleReCaptchaV3() {


        return Google::googleReCaptchaV3();


    }

}

if (!function_exists('googleAnalytics')) {

    function googleAnalytics() {
        return Google::googleAnalytics();
    }

}
