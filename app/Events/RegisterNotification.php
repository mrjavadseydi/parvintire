<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Mail;

class RegisterNotification
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($userLogin, $password)
    {
    
        $type = 'email';
        if (is_numeric($userLogin)) {
            $type = 'mobile';
        }
        
        if ($type == 'email') {
            try {
                SendMail::dispatch($userLogin, 'content', [
                    'title'       => 'ثبت نام شما با موفقیت انجام شد',
                    'description' => 'اطلاعات حساب شما به شرح زیر می باشد.',
                    'parameters'  => [
                        'email'    => $userLogin,
                        'password' => $password
                    ]
                ]);
            } catch (\Exception $error) {
        
            }
        }
        
        if ($type == 'mobile') {
            try {
                SendSms::dispatch($userLogin, implode("\n", [
                    siteName(),
                    "ثبت نام شما با موفقیت انجام شد",
                    "اطلاعات حساب شما",
                    $userLogin,
                    "رمز‌عبور :‌ " . $password,
                ]));
            } catch (\Exception $error) {
        
            }
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
