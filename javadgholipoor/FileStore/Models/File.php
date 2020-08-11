<?php

namespace LaraBase\FileStore\Models;

use LaraBase\CoreModel;
use LaraBase\Payment\Models\Transaction;

class File extends CoreModel
{

    protected $table = 'files';

    protected $guarded = [];

    public function can($transaction = null)
    {

        if ($this->type == '0')
            return true;

        if ($this->type == '1')
            if (auth()->check())
                return true;

        if ($this->type == '3') {
            if ($transaction == null) {
                $transaction = Transaction::where(['relation' => 'course', 'relation_id' => $this->post_id, 'status' => '1'])->first();
            }
            if ($transaction != null)
                return true;
        }

        if ($this->type == '4') {
            // TODO برای اعضای ویژه
        }

        return false;
    }

    public function title($transaction = null)
    {
        if ($this->type == '3') {
            if ($transaction == null) {
                $transaction = Transaction::where(['relation' => 'course', 'relation_id' => $this->post_id, 'status' => '1'])->first();
            }
            if ($transaction == null)
                return 'نیاز به پرداخت';
            else
                return 'پرداخت شده';
        } else if ($this->type == '2') {
            return 'اعضای ویژه';
        } else if ($this->type == '1') {
            if (!auth()->check()) {
                return 'اعضای سایت';
            }
        }
        return 'رایگان';
    }

    public function href($post)
    {
        return $post->href() . '/episode/' . $this->episode;
    }

    public function url()
    {
        if ($this->server == 'website') {
            return url('download/file/' . $this->id);
        } else {

        }
    }

}
