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

function uploaderHash($fullUrl) {
    $parse = parse_url($fullUrl);
    $url = $parse['host'];
    if (isset($parse['path']))
        $url .= $parse['path'];
    if (isset($parse['query'])) {
        $parts = explode('&', $parse['query']);
        sort($parts);
        $url .= implode($parts);
    }
    return md5($url . ip());
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
