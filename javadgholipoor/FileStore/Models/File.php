<?php

namespace LaraBase\FileStore\Models;

use LaraBase\CoreModel;

class File extends CoreModel
{

    protected $table = 'files';

    protected $guarded = [];

    public function can()
    {

        if ($this->type == '0')
            return true;

        if ($this->type == '1')
            if (auth()->check())
                return true;

        if ($this->type == '3') {

        }

        if ($this->type == '4') {

        }

        return false;
    }

    public function title()
    {
        if ($this->type == '3') {
            return 'نیاز به پرداخت';
        } else if ($this->type == '2') {
            return 'اعضای ویژه';
        } else if ($this->type == '1') {
            if (!auth()->check()) {
                return 'اعضای سایت';
            }
        }
        return 'رایگان';
    }

    public function href()
    {
        return '#no-code';
    }

}
