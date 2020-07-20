<?php

namespace LaraBase\Posts\Models;

use LaraBase\CoreModel;

class PostType extends CoreModel {

    protected $table = 'post_types';

    protected $fillable = [
        'label',
        'total_label',
        'type',
        'icon',
        'color',
        'boxes',
        'validations',
        'metas',
        'sitemap',
        'search',
        'created_at',
        'updated_at',
    ];

    public function href() {
        return url("posts/{$this->id}/{$this->type}/{$this->total_label}");
    }

    public function metas() {
        if (empty($this->metas))
            return [];

        return json_decode($this->metas, true);
    }

}
