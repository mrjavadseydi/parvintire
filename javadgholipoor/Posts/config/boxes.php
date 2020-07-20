<?php

return [

    'log' => [
            'title'      => 'ثبت کردن وقایع',
    ],

    'title' => [
        'title'      => 'عنوان فارسی',
        'box'        => 'input',
        'type'       => 'text',
        'id'         => '',
        'class'      => '',
        'attributes' => [
            'javad' => 'ok',
        ]
    ],

    'slug' => [
        'title' => 'نامک',
        'box'   => 'input',
        'type'  => 'text',
    ],

    'excerpt' => [
        'title' => 'توضیح کوتاه',
        'box'   => 'textarea',
        'type'  => 'text',
    ],

    'content' => [
        'title' => 'محتوا',
        'box'   => 'ckeditor',
    ],

    'categories' => [
        'title'      => 'دسته بندی ها',
        'box'        => 'categories',
    ],

    'tags' => [
        'title'                  => 'برچسب ها',
        'box'                    => 'tags',
        'url'                    => 'api/tags/search',
        'dir'                    => 'rtl',
        'maximumSelectionLength' => 7,
        'tags'                   => true
    ],

    'thumbnail' => [
        'title' => 'تصویر شاخص',
        'box'   => 'thumbnail'
    ],

    'gallery' => [
        'title' => 'گالری تصاویر',
        'box'   => 'gallery'
    ],

    'survey' => [
        'title'                  => 'نظرسنجی',
        'box'                    => 'survey',
        'url'                    => 'api/attributes/survey',
        'dir'                    => 'rtl',
        'maximumSelectionLength' => 10,
        'tags'                   => true
    ],

    'plan' => [
        'title'                  => 'پلن',
        'box'                    => 'plan',
        'radio'                  => 'attributeRadio'
    ],

    'attributes' => [
        'title'      => 'مشخصات',
        'box'        => 'attributes',
        'radio'      => 'attributeRadio'
    ],

    'product' => [
        'title'      => 'محصول',
        'box'        => 'product',
    ],

    'postalProduct' => [
        'title'      => 'محصول پستی',
        'box'        => 'postalProduct',
        'radio'      => 'productRadio'
    ],

    'downloadProduct' => [
        'title'      => 'محصول دانلودی',
        'box'        => 'downloadProduct',
        'radio'      => 'productRadio'
    ],

    'virtualProduct' => [
        'title'      => 'محصول مجازی',
        'box'        => 'virtualProduct',
        'radio'      => 'productRadio'
    ],

    'course' => [
        'title'      => 'دوره آموزشی',
        'box'        => 'course',
    ],

    'location' => [
        'title'      => 'موقعیت جغرافیایی',
        'box'        => 'location',
    ],

    'mobiles' => [
        'title'                  => 'تلفن همراه',
        'box'                    => 'mobiles',
        'dir'                    => 'rtl',
        'maximumSelectionLength' => 50,
        'tags'                   => true
    ],

    'phones' => [
        'title'                  => 'تلفن ثابت',
        'box'                    => 'phones',
        'dir'                    => 'rtl',
        'maximumSelectionLength' => 50,
        'tags'                   => true
    ],

    'sounds' => [
        'title' => 'فایل های صوتی',
        'box'   => 'sounds'
    ],

//    'preview' => [
//        'title'      => 'پیش نمایش',
//        'box'        => 'preview',
//    ],

    'aparat' => [
        'title' => 'کد آپارات ویدئو',
        'box'        => 'inputMeta',
        'type'       => 'text',
        'class'      => 'ltr',
    ],

    'course' => [
        'title'      => 'دوره آموزشی',
        'box'        => 'course',
    ],

    'menus' => [
        'title'      => 'فهرست ها',
        'box'        => 'menus',
    ],

    'telegram' => [
        'title'      => 'ارسال به تلگرام',
        'box'        => 'telegram',
    ],

    'textMetas' => [
        'title'      => 'متاهای متنی',
        'box'        => 'textMetas',
    ],

    'contentMetas' => [
        'title'      => 'متاهای محتوا',
        'box'        => 'contentMetas',
    ]

//    'parent' => [
//        'title'                  => 'مطالب مرتبط',
//        'box'                    => 'tags',
//        'url'                    => 'api/tags/search',
    //        'dir'                    => 'rtl',
//        'maximumSelectionLength' => 7
//    ],

    //    'priceFrom' => [
    //        'icon'       => 'icon-attach_money',
    //        'title'      => 'شروع قیمت از',
    //        'permission' => '',
    //    ],

    //    'code' => [
    //        'icon'       => 'icon-qrcode',
    //        'title'      => 'کد',
    //        'permission' => '',
    //    ],

    //    'courseStatus' => [
    //        'icon'       => 'icon-playlist_play',
    //        'title'      => 'وضعیت دوره',
    //        'permission' => '',
    //    ],

    //    'courseType' => [
    //        'icon'       => 'icon-playlist_play',
    //        'title'      => 'نوع دوره',
    //        'permission' => '',
    //    ],

    //    'videos' => [
    //        'icon'       => 'icon-youtuve',
    //        'title'      => 'ویدئو ها',
    //        'permission' => '',
    //    ],

    //    'teacher' => [
    //        'icon'       => 'icon-school',
    //        'title'      => 'مدرس',
    //        'permission' => '',
    //    ],

    //    'pageBuilder' => [
    //        'icon'       => 'icon-web',
    //        'title'      => 'صفحه ساز',
    //        'permission' => '',
    //    ],

    //    'duration' => [
    //        'icon'       => 'icon-alarm',
    //        'title'      => 'مدت زمان ویدئو',
    //        'permission' => '',
    //    ],

    //    'residenceRules' => [
    //        'icon'       => 'icon-rules',
    //        'title'      => 'قوانین اقامتگاه',
    //        'permission' => '',
    //    ],
    //
    //    'star' => [
    //        'icon'       => 'icon-star',
    //        'title'      => 'تعداد ستاره ها',
    //        'permission' => '',
    //    ],

    //    'website' => [
    //        'icon'       => 'icon-web',
    //        'title'      => 'آدرس وب‌سایت',
    //        'permission' => '',
    //    ],

    //    'adminName' => [
    //        'icon'       => 'icon-user-tie',
    //        'title'      => 'نام مسئول پذیرش اقامتگاه',
    //        'permission' => '',
    //    ],

];
