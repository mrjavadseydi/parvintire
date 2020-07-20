<?php

use LaraBase\Attributes\AttributeValues;

function attributeKeys() {
    $attachment = new AttributeValues();
    return $attachment->manager();
}


