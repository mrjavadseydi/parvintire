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
            setCache($key, $this->httpRequest(getRepository('api/v1/sms/getCredit'), []), 5);
        }

        return getCache($key);

    }

    public function send() {
        $this->httpRequest(getRepository('api/v1/sms/send'), [
            'text' => $this->text,
        ]);
    }

    public function sendPattern($pattern, $params) {
        $config = config(strtolower(env('APP_NAME')) . '_sms');
        if(isset($config[$pattern])) {
            $this->sendPatternByProject($config[$pattern], $params, $config);
        }
        return $this->httpRequest(getRepository('api/v1/sms/sendPattern'), array_merge([
            'pattern' => $pattern
        ], $params));
    }

    public function sendPatternByProject($patternCode, $params, $config)
    {
        $url = "http://rest.ippanel.com/v1/messages/patterns/send";
        $userAgent = sprintf("IPPanel/ApiClient/%s PHP/%s",  '1.0.1', phpversion());
        $headers = [
            'Accept: application/json',
            'Content-Type: application/json',
            sprintf("Authorization: AccessKey %s", $config['smsPanelAccessKey']),
            sprintf("User-Agent: %s", $userAgent)
        ];
        $curl = curl_init($url . "?" . http_build_query($params));
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // no need in php 5.1.3+.
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode([
            'pattern_code' => $patternCode,
            'originator' => $config['smsPanelSender'],
            'recipient' => $this->numbers[0],
            'values' => $params
        ]));
        $response = json_decode(curl_exec($curl), true);
        return $response;
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

    public function getPatterns()
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, getRepository('api/v1/sms/getPatterns/'. env('APP_KEY')));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        return curl_exec($curl);
    }

    public function setPatterns($patterns)
    {
       return $this->httpRequest(getRepository('api/v1/sms/createPatterns/'. env('APP_KEY')), [
           'patterns' => json_encode($patterns),
           'url' => url('')
       ]);
    }

}
