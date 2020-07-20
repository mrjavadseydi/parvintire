<?php

use LaraBase\Attributes\AttributeKeys;

function attributeValues() {
    $attachment = new AttributeKeys();
    return $attachment->manager();
}
