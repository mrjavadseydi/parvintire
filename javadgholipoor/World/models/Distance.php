<?php

namespace LaraBase\World\Models;

use LaraBase\CoreModel;

class Distance extends CoreModel
{
    
    protected $fillable = [
        "from",
        "to",
        "from_type",
        "to_type",
        "summary",
        "distance",
        "duration",
        "parent",
        "created_at",
        "updated_at",
    ];
    
}
