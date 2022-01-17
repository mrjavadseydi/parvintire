<?php

namespace LaraBase\Wallet\Controllers;

use Illuminate\Http\Request;
use LaraBase\CoreController;
use LaraBase\Payment\Models\Transaction;
use LaraBase\Wallet\Models\Wallet;

class WalletController extends CoreController
{

    public function charge(Request $request)
    {

        $output = [
            'status' => 'error',
            'message' => 'params'
        ];

        $output = validate($request, [
            'price' => 'required'
        ]);

        $price = toIRR($request->price);

        $referer = null;
        if (isset($_REQUEST['referer']))
            $referer = $_REQUEST['referer'];

        if ($price < 10000) {
            $output['status'] = 'error';
            $output['message'] = 'حداقل مبلغ برای شارژ 10,000 ریال می باشد.';
            return $output;
        }

        if ($output['status'] = 'success') {

            $userId = auth()->id();

            $wallet = Wallet::where([
                'user_id' => $userId,
                'status' => '0'
            ])->first();

            if ($wallet == null) {

                $wallet = Wallet::create([
                    'user_id' => $userId,
                    'price' => $price,
                    'description' => $request->description ?? 'شارژ'
                ]);

            } else {

                $wallet->update([
                    'price' => $price,
                    'description' => $request->description ?? 'شارژ'
                ]);

            }

            Transaction::where([
                'relation' => 'wallet',
                'relation_id' => $wallet->id,
                'status' => '0'
            ])->update(['status' => '2']);

            $token = generateToken(64);

            $transaction = Transaction::create([
                'user_id' => $userId,
                'relation' => 'wallet',
                'relation_id' => $wallet->id,
                'gateway' => siteGateway(),
                'price' => $price,
                'token' => $token,
                'ip' => ip()
            ]);

            $output['url'] = $transaction->url($referer);
            $output['message'] = 'به لینک پرداخت ریدایرکت کنید';

        }

        return $output;

    }

}
