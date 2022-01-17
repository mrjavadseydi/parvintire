<?php

namespace LaraBase\Store\Actions;

use LaraBase\Store\Models\Product;

trait Post {

    public function price() {
        $product = Product::where('post_id', $this->id)->first();
        return convertPrice($product->price, $product->currency);
    }

}
