<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TicketNotification
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($ticket, $comment)
    {
        
        if ($ticket->user_id == null) { // TODO اگر درخواست از طرف کاربر مهمان بود
            
            // ایمیل به همراه پاسخ به کاربر ارسال شود
            
        } else { // TODO اگر درخواست از طرف کاربر عضو بود
    
            // ایمیلی ارسال شود که لینک تیکت را دارد و کاربر به سایت مراجعه کند و پاسخ خود را مشاهده کند
    
        }
    
        if ($ticket->mobile != null) {
            SendSms::dispatch($ticket->mobile, $comment);
        }
    
        if ($ticket->email != null) {
            SendMail::dispatch($ticket->email, 'content', [
                'title'       => $ticket->subject,
                'description' => $comment
            ]);
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
