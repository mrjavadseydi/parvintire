<?php

namespace LaraBase\Payment;

use function GuzzleHttp\Psr7\build_query;
use ZarinPal;
use LaraBase\Payment\Models\Transaction;

class Manager {

    private
        $gateway = null,
        $params  = null,
        $request = false
    ;

    public function __construct($gateway = 'ZarinPal') {

        $gatewayClass = "LaraBase\\Payment\\Gateways\\{$gateway}";
        $this->gateway = new $gatewayClass();

    }

    public function orderId($id) {
        $this->params['orderId'] = $id;
        return $this;
    }

    public function transId($id) {
        $this->params['transId'] = $id;
        return $this;
    }

    public function amount($amount) {
        $this->params['amount'] = $amount;
        return $this;
    }

    public function description($description) {
        $this->params['description'] = $description;
        return $this;
    }

    public function email($email) {
        $this->params['email'] = $email;
        return $this;
    }

    public function mobile($mobile) {
        $this->params['mobile'] = $mobile;
        return $this;
    }

    public function callbackUrl($callbackUrl = null, $params = []) {

        if (empty($params))
            $this->params['callbackUrl'] = $callbackUrl;
        else
            $this->params['callbackUrl'] = $callbackUrl . "?" . build_query($params);

        return $this;

    }

    private function request() {
        if (!$this->request) {
            $this->request = true;
            return $this->gateway->request($this->params);
        }
    }

    public function callback($callback) {
        call_user_func($callback, $this->request());
        return $this;
    }

    public function send() {
        $this->request();
        $this->gateway->send();
    }

    public function list() {
        return [
            'ZarinPal',
            'Mellat',
        ];
    }

    public function verify($params, $callback) {
        return call_user_func($callback, $this->gateway->verify($params));
    }

}
