<?php


namespace LaraBase\Categories\Actions;


trait Category {

    public function image() {
        if (!empty($this->image))
            return url($this->image);

        return image('category.png', 'admin');
    }

    public function href() {
        return url("categories/{$this->id}/{$this->slug}");
    }

}
