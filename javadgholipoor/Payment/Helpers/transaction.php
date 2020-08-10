<?php

use LaraBase\Payment\Models\Transaction;

function addTransaction($userId, $relation, $relationId, $price, $gateway) {

    $token = generateUniqueToken();

    Transaction::where([
        'user_id' => $userId,
        'relation' => $relation,
        'relation_id' => $relationId,
        'status' => '0'
    ])->update(['status' => '3']);

    return Transaction::create([
        'user_id' => $userId,
        'relation' => $relation,
        'relation_id' => $relationId,
        'gateway' => $gateway ?? getOption('gateway'),
        'price' => $price,
        'token' => $token,
        'ip' => ip(),
    ]);

}
