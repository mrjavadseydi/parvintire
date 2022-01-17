<?php

namespace LaraBase\Telegram;

class Manager {

    protected $message;
    protected $photo = null;
    protected $tags;
    protected $code = false;

    public function message($message) {
        $this->message = $message;
        return $this;
    }

    public function photo($url) {
        $this->photo = $url;
        return $this;
    }

    public function code(array $message) {
        $this->code = true;
        $this->message = $message;
        return $this;
    }

    public function tags($tags = []) {
        $this->tags = $tags;
        return $this;
    }

    private function hashTags($tags) {
        return $tags;
    }

    private function build() {
        $tags = $this->tags;
        $message = $this->message;

        if (!empty($message))
            if (!is_array($message))
                $message = [$message];

        if (empty($tags)) {

            if ($this->code)
                $message = print_r($message, true);
            else
                $message = implode("\n", $message);

        } else {

            if (!is_array($tags))
                $tags = [$tags];

            $tags = $this->hashtags($tags);

            $hashTags['tags'] = "#" . implode(" #", $tags);

            if ($this->code) {
                $message = print_r(array_merge($message, $hashTags), true);
            } else {
                $message = implode("\n", array_merge($message, $hashTags));
            }

        }

        return $message;
    }

    public function temporarySend($token, $chatId) {
        $this->send($token, $chatId);
    }

    public function sendToGroup()
    {
        $telegramBotToken = getOption('telegram-bot-token');
        if (!empty($telegramBotToken)) {
            $telegramGroupChatId = getOption('telegram-group-chat-id');
            $this->send($telegramBotToken, $telegramGroupChatId);
        }
    }

    public function sendToChannel()
    {
        $telegramBotToken = getOption('telegram-bot-token');
        if (!empty($telegramBotToken)) {
            $telegramGroupChatId = getOption('telegram-channel-chat-id');
            $this->send($telegramBotToken, $telegramGroupChatId);
        }
    }



    public function send($token, $chatId)
    {
        $method = 'sendMessage';
        if ($this->photo != null)
            $method = 'sendPhoto';

        $url = getRepository("api/v1/telegram/{$method}");
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 5);
        curl_setopt($curl, CURLOPT_POSTFIELDS, [
            'token'   => $token,
            'chatId'  => $chatId,
            'message' => $this->build(),
            'photoUrl' => $this->photo
        ]);
        $response = json_decode(curl_exec($curl));
        curl_close($curl);
        return $response;
    }

}

