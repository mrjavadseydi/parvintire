<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SendTelegram
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($message)
    {
        
        $accessKey = getOption('telegramLogBot');
        if (!empty($accessKey)) {
    
            $params =  array(
                'api'    => $accessKey,
                'to'     => getOption('telegramLogBotToken'),
                'text'   => (is_array($message) ? print_r($message, true) : $message),
            );
    
            $ch = curl_init( "https://ircodex-dev.ir/telegram/api/v1/sendMessage" );
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params );
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            $response[] = json_decode(curl_exec($ch));
            curl_close($ch);
        
        }
    
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
