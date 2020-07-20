<?php

namespace LaraBase\Sms;

class Manager {

    private $text = null;
    private $numbers  = [];

    public function text($text) {
        $this->text = str_replace(PHP_EOL, "\n", $text);
        return $this;
    }

    public function numbers($numbers) {
        if (!is_array($numbers))
            $this->numbers = [$numbers];
        else
            $this->numbers = $numbers;

        return $this;
    }

    public function getCredit() {

        if (env('APP_DEBUG')) {
            return json_encode([
                'credit' => 0,
                'currency' => 'debug'
            ]);
        }

        $key = 'smsCredit';

        if (!hasCache($key)) {
            setCache($key, $this->httpRequest(getRepository('api/sms/getCredit'), []), 5);
        }

        return getCache($key);

    }

    public function send() {
        $this->httpRequest(getRepository('api/sms/send'), [
            'text' => $this->text,
        ]);
    }

    public function sendPattern($pattern, $params) {
        return $this->httpRequest(getRepository('api/sms/sendPattern'), array_merge([
            'pattern' => $pattern
        ], $params));
    }

    private function httpRequest($url, $params) {
        $curl = curl_init();
        $params = array_merge($params, [
            'appKey' => env('APP_KEY'),
            'numbers' => json_encode($this->numbers),
            'messageKey' => microtime()
        ]);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        return curl_exec($curl);
    }

}
