<?php

use LaraBase\Wallet\Models\Wallet;

function getWalletCredit($userId = null)
{

    if (empty($userId)) {
        $userId = auth()->id();
    }

    $wallet = Wallet::where(['user_id' => $userId, 'status' => '1'])->orderBy('updated_at', 'desc')->first();

    if ($wallet == null)
        return 0;

    return $wallet->remained;

}

function addWallet($price, $userId, $description = null)
{

    $wallet = Wallet::create([
        'user_id' => $userId,
        'price' => $price,
        'description' => $description,
        'status' => '1',
        'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
    ]);

    updateWalletRemained($userId);
    return $wallet;

}

function payWithWallet($price, $userId, $relation, $relationId, $description)
{

    $price = toIRR($price);

    $wallet = Wallet::create([
        'user_id' => $userId,
        'price' => $price,
        'description' => $description,
        'status' => '1',
        'updated_at' => \Carbon\Carbon::now()->toDateTimeString()
    ]);

    $transaction = $wallet->addTransaction($relation, $relationId);

    updateWalletRemained($userId);

    return [
        'wallet' => $wallet,
        'transaction' => $transaction
    ];

}

function updateWalletRemained($userId = null)
{

    if (empty($userId)) {
        $userId = auth()->id();
    }

    $where = [
        'user_id' => $userId,
        'status' => '1'
    ];

    $wallet = Wallet::where($where)->orderBy('updated_at', 'desc')->first();
    $wallet->update(['remained' => Wallet::where($where)->sum('price')]);

}
