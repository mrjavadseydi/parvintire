<?php

use LaraBase\Helpers\Validation;

if(!function_exists('checkMail')){
    function checkMail($email){
        return Validation::email($email);
    }
}

if(!function_exists('checkMobile')){
    function checkMobile($number){
        return Validation::mobile($number);
    }
}

if(!function_exists('checkNationalCode')){
    function checkNationalCode($nationalCode){
        return Validation::nationalCode($nationalCode);
    }
}

if(!function_exists('validate')){
    function validate($request, $rules, $messages = []){
        return Validation::validate($request, $rules, $messages);
    }
}
