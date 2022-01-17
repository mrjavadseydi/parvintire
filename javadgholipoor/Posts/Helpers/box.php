<?php

function boxAttributes($box) {
    $attributes = null;
    foreach ($box['attributes'] ?? [] as $attrKey => $attrValue) {
        $attributes .= $attrKey . '="' . $attrValue . '" ';
    }
    return $attributes;
}

function boxClasses($box, $moreClasses = null) {
    return (isset($box['class']) ? 'class="'.$box['class'].' '.$moreClasses.'"' : (empty($moreClasses) ? "" : 'class="'.$moreClasses.'"'));
}

function boxIds($box, $moreIds = null) {
    return (isset($box['id']) ? 'id="'.$box['id'].' '.$moreIds.'"' : (empty($moreIds) ? "" : 'id="'.$moreIds.'"'));
}

function boxType($box) {
    return (isset($box['type']) ? 'type="'.$box['type'].'"' : 'type="text"');
}

function getProjectBoxes() {
    $appName = env('APP_NAME');
    $appNameToLower = strtolower($appName);
    
    if (file_exists(base_path("projects/{$appName}/config/{$appNameToLower}_boxes.php"))) {
        return config("{$appNameToLower}_boxes");
    }
    
    return [];
}
