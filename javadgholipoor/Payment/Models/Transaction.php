<?php


namespace LaraBase\Payment\Models;


use LaraBase\CoreModel;

class Transaction extends CoreModel {

    use \LaraBase\Payment\Actions\Transaction;

    protected $table = 'transactions';

    protected $fillable = [
        'user_id',
        'relation',
        'relation_id',
        'gateway',
        'currency',
        'price',
        'status',
        'reference_id',
        'token',
        'authority',
        'information',
        'code',
        'ip',
        'created_at',
        'updated_at',
    ];

    public function url($referer = null)
    {
        return url("payment/request/{$this->id}/{$this->token}" . ($referer == null ? '' : '?referer=' . urlencode($referer)));
    }

    public function scopePayed($query)
    {
        $query->where('status', '1');
    }

    public function scopeSuccess($query)
    {
        $query->where('status', '1');
    }

}
