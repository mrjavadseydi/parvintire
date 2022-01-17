<?php

namespace LaraBase\Payment\Controllers;

use App\Events\NewOrder;
use LaraBase\Auth\Models\User;
use LaraBase\Auth\Models\UserMeta;
use LaraBase\CoreController;
use LaraBase\Payment\Gateway;
use LaraBase\Payment\Models\Transaction;
use LaraBase\Store\Controllers\OrderController;
use LaraBase\Store\Models\Order;
use LaraBase\Store\Models\OrderShippingStatus;
use LaraBase\Store\Models\Product;
use LaraBase\Wallet\Models\Wallet;

class PaymentController extends CoreController {

    private $noGateway = [
        'wallet',
        'home'
    ];

    public function request($id, $token) {

        $referer = null;
        if (isset($_REQUEST['referer']))
            $referer = $_REQUEST['referer'];

        $transaction = Transaction::where(['id' => $id, 'token' => $token])->first();

        if ($transaction != null) {

            $user = auth()->user();
            $gateway = $transaction->gateway;
            $amount = $transaction->price();

            if ($user->id != $transaction->user_id) {
                return $this->failed('این تراکنش متعلق به فرد دیگری می باشد.', 'بازگشت به خانه', url('/'));
            }

            if ($transaction->status == '1') {
                return $this->failed('تراکنش قبلا پرداخت شده است.', 'بازگشت به خانه', url('/'));
            }

            if ($transaction->status != '0') {
                return $this->failed('تراکنش قابل پرداخت نمی باشد. ممکن است این تراکنش منقضی شده باشد.', 'بازگشت به خانه', url('/'));
            }

            $token = generateUniqueToken();

            if (in_array($gateway, $this->noGateway)){
                if (method_exists($this, $gateway)) {
                    return $this->$gateway($transaction);
                }
            } else {
                return gateway($gateway)
                    ->transId($id)
                    ->orderId(strtotime($transaction->updated_at))
                    ->amount($amount)
                    ->description("پرداخت تراکنش با شناسه {$id}")
                    ->callbackUrl(route('paymentVerify'), [
                        'id'      => $id,
                        'token'   => $token,
                        'referer' => $referer
                    ])
                    ->callback(function ($args) use ($transaction, $token) {

                        $update = ['token' => $token];

                        if (isset($args['authority'])) {
                            $update['authority'] = $args['authority'];
                        }

                        $transaction->update($update);

                    })
                    ->send();
            }

        }

        return $this->failed('تراکنش یافت نشد', 'بازگشت به خانه', url('/'));

    }

    public function verify() {

        $id = $_REQUEST['id'];
        $token = $_REQUEST['token'];
        $transaction = Transaction::where(['id' => $id, 'token' => $token])->first();

        if ($transaction != null) {

            $gateway = $transaction->gateway;
            $amount  = $transaction->price();

            if (in_array($gateway, $this->noGateway)) {
                if (method_exists($this, $gateway)) {
                    if ($transaction->status == '1') {
                        return $this->output(true, $transaction, ['message' => 'پرداخت با موفقیت انجام شد']);
                    } else {
                        return $this->output(false, $transaction, ['message' => 'پرداخت ناموفق']);
                    }
                }
            } else {
                return gateway($gateway)->verify(['amount' => $amount, 'request' => $_REQUEST, 'orderId' => strtotime($transaction->updated_at)], function ($result) use ($transaction) {

                    if ($transaction->status != 1) {
                        if ($result['status'] == 'success') {

                            $update = ['status' => '1', 'information' => json_encode($_REQUEST)];

                            if (isset($result['referenceId']))
                                $update['reference_id'] = $result['referenceId'];

                            if (isset($result['code']))
                                $update['code'] = $result['code'];

                            $transaction->update($update);

                        } else {

                            $update = ['status' => 2, 'information' => json_encode($_REQUEST)];

                            if (isset($result['code']))
                                $update['code'] = $result['code'];

                            $transaction->update($update);

                            return $this->output(false, $transaction, $result);

                        }
                    }

                    return $this->output(true, $transaction, $result);

                });
            }

        }

        return $this->failed('تراکنش یافت نشد', 'بازگشت به خانه', url('/'));

    }

    public function getTransaction()
    {
        if (isset($_GET['id']) && $_GET['token']) {
            return Transaction::where(['id' => $_GET['id'], 'token' => $_GET['token']])->first();
        }
        return null;
    }

    public function failed($message, $buttonText, $url) {
        return templateView('payment.failed', [
            'url' => $url,
            'message' => $message,
            'buttonText' => $buttonText,
            'transaction' => $this->getTransaction()
        ]);
    }

    public function success($message, $buttonText, $url) {
        return templateView('payment.success', [
            'url' => $url,
            'message' => $message,
            'buttonText' => $buttonText,
            'transaction' => $this->getTransaction()
        ]);
    }

    public function output($success, $transaction, $data)
    {

        if ($success) {
            $method = $transaction->relation . "Relation";
            if (method_exists($this, $method)) {
                $this->$method($transaction);
            }
        }

        $status = $success ? 'success' : 'error';
        if (isset($_REQUEST['referer'])) {
            if (!empty($_REQUEST['referer'])) {
                $referer = $_REQUEST['referer'];
                $parse = parse_url($referer);
                if (isset($parse['query'])) {
                    $referer .= "&payment={$status}&relation={$transaction->relation}&token={$transaction->token}&transaction_id={$transaction->id}";
                } else {
                    $referer .= "?payment={$status}&relation={$transaction->relation}&token={$transaction->token}&transaction_id={$transaction->id}";
                }
                return redirect($referer);
            }
        }

        if ($success)
            return $this->success('تراکنش با موفقیت پرداخت شد', 'بازگشت به خانه', url('/'));

        return $this->failed($data['message'] ?? 'تراکنش یافت نشد', 'بازگشت به خانه', url('/'));
    }

    public function redirectToVerify($transaction)
    {
        return redirect("payment/verify?id={$transaction->id}&token={$transaction->token}");
    }

    public function wallet($transaction)
    {
        if (auth()->check()) {
            if (auth()->id() == $transaction->user_id) {
                if (getWalletCredit() >= $transaction->price) {
                    $wallet = addWallet(-$transaction->price, $transaction->user_id, 'پرداخت تراکنش ' . $transaction->id);
                    $transaction->update([
                        'reference_id' => $wallet->id,
                        'status' => '1',
                    ]);
                }
            }
        }
        return $this->redirectToVerify($transaction);
    }

    public function home($transaction)
    {
        if (auth()->check()) {
            if (auth()->id() == $transaction->user_id) {
                $transaction->update([
                    'reference_id' => 'home',
                    'status' => '1',
                ]);
            }
        }
        return $this->redirectToVerify($transaction);
    }

    public function walletRelation($transaction)
    {
        $wallet = Wallet::where('id', $transaction->relation_id)->first();
        $wallet->update(['status' => '1']);
        updateWalletRemained($wallet->user_id);
    }

    public function orderRelation($transaction)
    {
        $order = Order::find($transaction->relation_id);
        if ($order->status != 1) {
            $order->update([
                'payed_price' => $transaction->price,
                'status' => '1'
            ]);
            foreach ($order->shippings() as $shipping) {
                OrderShippingStatus::create([
                    'order_id' => $order->id,
                    'order_shipping_id' => $shipping->id
                ]);
            }
            $carts = $order->carts();
            $products = Product::whereIn('product_id', $carts->pluck('product_id')->toArray())->get();
            foreach ($products as $product) {
                $cart = $carts->where('product_id', $product->product_id)->first();
                Product::where('product_id', $product->product_id)->update(['stock' => $product->stock - $cart->count]);
            }
            $address = $order->address();
            $address->update(['success_orders' => $address->success_orders + 1]);
            $nextOrder = Order::where(['user_id' => $order->user_id, 'status' => '4'])->first();
            NewOrder::dispatch($order, $transaction);
            if ($nextOrder != null) {
                $nextOrder->update(['status' => '0']);
            }
            if (!empty($order->partner)) {
                $partner = User::find($order->partner);
                if ($partner != null) {
                    $is = UserMeta::where([
                        'user_id' => $partner->id,
                        'key' => 'salesCooperation'
                    ])->first();
                    if ($is != null) {
                        if ($is->value == 'enable') {
                            $getPercent = UserMeta::where([
                                'user_id' => $partner->id,
                                'key' => 'partnerPercent'
                            ])->first();
                            $percent = 1;
                            if ($getPercent != null) {
                                $percent = intval($getPercent->value);
                            }
                            $price = convertPrice($transaction->price);
                            $profit = ($price * $percent) / 100;
                            addWallet($profit, $partner->id,'سود حاصل از همکاری در فروش سفارش شماره ' . $order->id);
                        }
                    }
                }
            }
        }
    }

}
