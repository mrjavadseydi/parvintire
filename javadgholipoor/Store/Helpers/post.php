<?php

use LaraBase\Store\Models\Product;

// TODO Optimize, Cache
function postPrice($postId) {
    $product = Product::where(['post_id' => $postId])->first();
    return $product->price();
}
