<?php

function needs_address($order_type){
    if($order_type === null)
        return true;
    $ship_config = config('shipping');
    return $ship_config['ship_types'][$ship_config['order_types'][$order_type]['ship']]['address'];
}
function needs_postage($order_type){
    if($order_type === null)
        return true;
    $ship_config = config('shipping');
    return $ship_config['ship_types'][$ship_config['order_types'][$order_type]['ship']]['postage'];
}

function needs_gateway($order_type){
    if($order_type === null)
        return true;
    $ship_config = config('shipping');
    return $ship_config['payment_types'][$ship_config['order_types'][$order_type]['payment']]['gateway'];
}

function needs_name_code($order_type){
    if($order_type === null)
        return true;
    return $order_type=="at_shop";
}

function shipping() {
    return \LaraBase\Store\Models\Shipping::all();
}
