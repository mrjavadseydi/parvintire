<?php

use \LaraBase\Store\Models\ProductFile;

function getProductTypes() {
    return config('product.types');
}

function productFiles($postId) {
    return ProductFile::where(['post_id' => $postId])->with('attachment')->orderBy('sort', 'asc')->get();
}
