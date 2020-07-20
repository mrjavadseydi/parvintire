<?php

return [

    'postTypes' => [
        [
            'id'          => 1,
            'label'       => 'محصول',
            'total_label' => 'محصولات',
            'type'        => 'products',
            'icon'        => 'icon-shopping_cart',
            'boxes'       => '["log","title","slug","excerpt","content","categories","tags","thumbnail","gallery","attributes","product","postalProduct","telegram"]',
            'validations' => '{"thumbnail":{"key":"thumbnail","in":"1","validations":"mimes:png,jpg,jpeg,gif,PNG,JPG,JPEG,GIF|min:0|max:4096"},"gallery":{"key":"gallery","in":"1","validations":"mimes:png,jpg,jpeg,gif,PNG,JPG,JPEG,GIF|min:0|max:4096"},"links":{"key":"links","in":"1","validations":"mimes:png,jpg,jpeg,gif,PNG,JPG,JPEG,GIF,zip,rar,pdf,mpga,mp4||min:0|max:51200"}}',
            'sitemap'     => '1',
            'search'      => '1',
        ],
        [
            'id'          => 2,
            'label'       => 'مقاله',
            'total_label' => 'مقالات',
            'type'        => 'articles',
            'icon'        => 'icon-pen',
            'boxes'       => '["log","title","slug","excerpt","content","categories","tags","thumbnail","telegram"]',
            'validations' => '{"thumbnail":{"key":"thumbnail","in":"1","validations":"mimes:png,jpg,jpeg,gif,PNG,JPG,JPEG,GIF|min:0|max:4096"},"gallery":{"key":"gallery","in":"1","validations":"mimes:png,jpg,jpeg,gif,PNG,JPG,JPEG,GIF|min:0|max:4096"},"links":{"key":"links","in":"1","validations":"mimes:png,jpg,jpeg,gif,PNG,JPG,JPEG,GIF,zip,rar,pdf,mpga,mp4||min:0|max:51200"}}',
            'sitemap'     => '1',
            'search'      => '1',
        ],
        [
            'id'          => 3,
            'label'       => 'اسلایدر',
            'total_label' => 'اسلایدر ها',
            'type'        => 'sliders',
            'icon'        => 'icon-images',
            'boxes'       => '["log","title","categories","thumbnail"]',
            'validations' => '{"thumbnail":{"key":"thumbnail","in":"1","validations":"mimes:png,jpg,jpeg,gif,PNG,JPG,JPEG,GIF|min:0|max:4096"},"gallery":{"key":"gallery","in":"1","validations":"mimes:png,jpg,jpeg,gif,PNG,JPG,JPEG,GIF|min:0|max:4096"},"links":{"key":"links","in":"1","validations":"mimes:png,jpg,jpeg,gif,PNG,JPG,JPEG,GIF,zip,rar,pdf,mpga,mp4||min:0|max:51200"}}',
        ],
        [
            'id'          => 4,
            'label'       => 'سوال',
            'total_label' => 'سوالات متداول',
            'type'        => 'faq',
            'icon'        => 'icon-question',
            'boxes'       => '["log","title","slug","excerpt","content","categories","tags","thumbnail","telegram"]',
            'validations' => '{"thumbnail":{"key":"thumbnail","in":"1","validations":"mimes:png,jpg,jpeg,gif,PNG,JPG,JPEG,GIF|min:0|max:4096"},"gallery":{"key":"gallery","in":"1","validations":"mimes:png,jpg,jpeg,gif,PNG,JPG,JPEG,GIF|min:0|max:4096"},"links":{"key":"links","in":"1","validations":"mimes:png,jpg,jpeg,gif,PNG,JPG,JPEG,GIF,zip,rar,pdf,mpga,mp4||min:0|max:51200"}}',
        ],
    ],

    'themes' => [
        'admin'    => 'default',
        'template' => 'digishop',
        'auth'     => 'default',
        'uploader' => 'default',
        'mail'     => 'default'
    ]

];
