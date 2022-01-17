<?php

namespace LaraBase\FileStore\Models;

use LaraBase\CoreModel;

class FileGroup extends CoreModel
{

    protected $table = 'files_groups';

    protected $guarded = [];

    public function files()
    {
        return $this->hasMany(File::class, 'group_id');
    }

}
