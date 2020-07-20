<?php

use LaraBase\World\models\Province;

function isActiveWorld() {
    return config('world.active');
}

function getProvinces($where = []) {
    if (empty($where))
        $where['country_id'] = 244;
    return Province::where($where)->get();
}
