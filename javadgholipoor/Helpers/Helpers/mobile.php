<?php

use Larabase\Helper\Mobile;

if(!function_exists('sanitizeMobile')){
    function sanitizeMobile($phone){
        return Mobile::sanitize($phone);
    }
}
