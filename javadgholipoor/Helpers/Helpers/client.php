<?php

use LaraBase\Helpers\Client;

if(!function_exists('ip')){
    function ip(){
        return Client::ip();
    }
}
