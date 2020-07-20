<?php

use LaraBase\Attributes\Attributes;

function attributes() {
    $attachment = new Attributes();
    return $attachment->manager();
}

function attributeTypes() {
    return config('attributes.type');
}

function getProductsAttributes() {
    return Attribute::where('type', 'product')->get();
}

function getProjectValueMetas() {
    $appName = env('APP_NAME');
    $appNameToLower = strtolower($appName);
    
    if (file_exists(base_path("projects/{$appName}/config/{$appNameToLower}_values.php"))) {
        return config("{$appNameToLower}_values");
    }
    
    return [];
}
