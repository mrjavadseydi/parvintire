<?php

// pardakht hozuri = presence

return [
    'order_types' => [
        'at_home' => [
            'title' => 'پرداخت درب منزل',
            'ship' => 'post',
            'payment' => 'presence',
        ],
        'at_shop' => [
            'title' => 'تحویل درب فروشگاه (پرداخت آنلاین و بدون هزینه پست)',
            'ship' => 'none',
            'payment' => 'online',
        ],
        'online_post' => [
            'title' => 'پراخت آنلاین و ارسال پستی',
            'ship' => 'post',
            'payment' => 'online',
        ],
    ],
];
