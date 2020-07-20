<?php

use LaraBase\Helpers\Date;

function jalaliMonth() {
    return Date::jalaliMonth();
}

function rePairDate($date, $delmiter = '-'){
    return Date::repaire($date, $delmiter);
}
