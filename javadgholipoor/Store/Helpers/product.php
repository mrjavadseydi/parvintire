<?php

use \LaraBase\Store\Models\ProductFile;

function productTypes() {
    return config('product.types');
}

function getProductTypes() {
    return config('product.types');
}

function productFiles($postId) {
    return ProductFile::where(['post_id' => $postId])->with('attachment')->orderBy('sort', 'asc')->get();
}

function productsPostTypes() {
    // TODO بصورت کش دائم و حذف در هنگام ویرایش حذف و ایجاد پست تایپ ها
    return [];
}
