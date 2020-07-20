<?php

namespace LaraBase\Wallet\Models;

use LaraBase\CoreModel;
use LaraBase\Payment\Models\Transaction;

class Wallet extends CoreModel
{

    protected $table = 'wallets';

    protected $fillable = [
        'id',
        'user_id',
        'price',
        'remained',
        'description',
        'status',
        'created_at',
        'updated_at',
    ];

    public function addTransaction($relation, $relationId)
    {

        return Transaction::create([
            'user_id' => $this->user_id,
            'relation' => $relation,
            'relation_id' => $relationId,
            'gateway' => 'wallet',
            'price' => $this->price,
            'status' => '1',
            'payed' => '1',
            'reference_id' => $this->id,
            'ip' => ip(),
        ]);

    }

}
