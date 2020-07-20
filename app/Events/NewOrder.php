<?php

namespace App\Events;

use http\Client\Curl\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewOrder
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($order, $transaction)
    {
        $currency = siteCurrency('more');
        $user = \LaraBase\Auth\Models\User::find($order->user_id);
        $price = toPersian(number_format(convertPrice($transaction->price))) . ' ' . $currency;
        telegram()->message([
            'سفارش جدید ثبت شد',
            'مبلغ سفارش : ' . $price
        ])->sendToGroup();
        $siteMobile = siteMobile();
        if (checkMobile($siteMobile)) {
            sms()->numbers($siteMobile)->sendPattern('newOrder', [
                'price' => $price
            ]);
        }
        if (checkMobile($user->mobile)) {
            sms()->numbers($user->mobile)->sendPattern('userNewOrder', [
                'name' => $user->name ?? 'کاربر',
                'ref' => $transaction->reference_id
            ]);
        } else {
            if (checkMail($user->email)) {
                SendMail::dispatch($user->email, 'newOrder', [
                    'title' => 'سفارش شما با موفقیت ثبت شد',
                    'name' => $user->name ?? 'کاربر',
                    'referenceId' => $transaction->reference_id
                ]);
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
