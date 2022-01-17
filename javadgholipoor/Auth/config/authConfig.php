<?php

return [

    'passwordLength' => 8,
    'emailMaxLength' => 191,
    'sendSmsTime'    => 5,  // by Minutes

    'login' => [
        'self'     => true,
        'username' => true,
        'mobile'   => true,
        'email'    => true,
        'google'   => true
    ],

    'register' => [
        'self'     => true,
        'username' => true,
        'mobile'   => true,
        'email'    => true,
    ],

    'recovery' => [
        'self'     => true,
        'mobile'   => true,
        'email'    => true,
    ],

    'google' => [
        'reCaptchaV3' => [
            'active'  => true,
            'secret'  => '6LeA4MEUAAAAADz7fKZSsopqk5TkWn0PToISFuGE',
            'siteKey' => '6LeA4MEUAAAAAM6C1awkWwMCEd5viQNEVKFa2KVd',
            'timeout' => 30,
        ],
        'sign' => [
            'active'    => true,
            'clientId'      => '', // in config/services.php,
            'clientSecret'  => '', // in config/services.php
            'redirect'      => '', // in config/services.php
        ]
    ],

    'telegram' => [
        'log' => true
    ]

];
