<?php

use \LaraBase\Uploader\Uploader;

function uploader() {
    $uploader = new Uploader();
    return $uploader->manager();
}

function uploaderOptions() {
    $get = \LaraBase\Options\Models\Option::where('key', 'uploaderOptions')->first();
    if ($get != null)
        return json_decode($get->value, true);
}

function uploaderTheme() {
    return getTheme('uploader');
}

function uploaderLoading() {
    return image('loading.gif', 'uploader');
}

function uploaderIcon($extension) {
    $icons = config('uploader.icons');
    $name = "{$extension}.png";
    if (isset($icons[$extension]))
        $name = $icons[$extension];

    return $name;
}

function uploaderSelectIcon() {
    return image('selected.gif', 'uploader');
}

function uploaderDeleteIcon() {
    return image('delete.png', 'uploader');
}

function uploaderGenerateNameBySize($nameOrPath, $width, $height) {
    $parts = explode('/', $nameOrPath);
    $index = count($parts) - 1;
    $parts[$index] = "{$width}x{$height}-{$parts[$index]}";
    return implode('/', $parts);
}
