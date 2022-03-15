<?php

function needs_address($order_type){
    $ship_config = config('shipping');
    return $ship_config['ship_types'][$ship_config['order_types'][$order_type]['ship']]['address'];
}

function needs_gateway($order_type){
    $ship_config = config('shipping');
    return $ship_config['payment_types'][$ship_config['order_types'][$order_type]['payment']]['gateway'];
}

function shipping() {
    return \LaraBase\Store\Models\Shipping::all();
}
