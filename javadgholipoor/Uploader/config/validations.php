<?php

return [
    'mimes' => [
        'type' => 'checkbox',
        'col'  => 'col-1',
        'values' => [
            'mpga',
            'mp4',
            'jpeg',
            'jpg',
            'png',
            'gif',
            'zip',
            'pdf',
            'txt'
        ]
    ],
    'min' => [
        'type' => 'radio',
        'col'  => 'col-1',
        'values' => [
              0,
              1024,
              2048,
              4096,
              8192,
              16384,
              32768,
              65536,
              131072,
              262144,
              524288,
              1048576
        ]
    ],
    'max' => [
        'type' => 'radio',
        'col'  => 'col-1',
        'values' => [
            0,
            1024,
            2048,
            4096,
            8192,
            16384,
            32768,
            65536,
            131072,
            262144,
            524288,
            1048576
        ]
    ],
];
