<?php

return [

    'admin' => [
        'title' => 'مدیریت',
        'permissions' => [
            'administrator' => [
                'title' => 'مدیرکل'
            ],
            "controlPanel" => [
                'title' => 'مشاهده پنل مدیریت'
            ],
            "configuration" => [
                'title' => 'پیکربندی'
            ],
            'updateCore' => [
                'title' => 'بروز رسانی هسته پروژه'
            ],
            'updateDatabase' => [
                'title' => 'همگام سازی پایگاه'
            ],
            'updateThemes' => [
                'title' => 'بروز رسانی قالب‌ها'
            ],
            'initProject' => [
                'title' => 'پیکربندی پروژه'
            ]
        ]
    ],

    'users' => [
        'title' => 'کاربران',
        'permissions' => [
            'showUser' => [
                'title' => 'مشاهده کاربر'
            ],
            'createUser' => [
                'title' => 'افزودن کاربر'
            ],
            'updateUser' => [
                'title' => 'ویرایش کاربر',
            ],
            'deleteUser' => [
                'title' => 'حذف کاربر',
            ],
            'verifyEmailUser' => [
                'title' => 'تایید ایمیل کاربر',
            ],
            'verifyMobileUser' => [
                'title' => 'تایید موبایل کاربر',
            ],
            'switchUser' => [
                'title' => 'گرفتن سطح دسترسی کاربران'
            ],
            'blockUser' => [
                'title' => 'مسدود کردن کاربر'
            ],
            'roles' => [
                'title' => 'نقش ها'
            ],
            'canSetAllRoles' => [
                'title' => 'اختصاص تمام نقش ها'
            ],
            'canSetSelfRole' => [
                'title' => 'اختصاص نقش خود'
            ],
            'permissions' => [
                'title' => 'مجوز ها'
            ],
            'canSetAllPermissions' => [
                'title' => 'اختصاص تمام دسترسی ها'
            ]
        ]
    ],

    'files' => [
        'title' => 'فایل ها',
        'permissions' => [
            'addFile' => [
                'title' => 'افزودن فایل'
            ],
            'getFile' => [
                'title' => 'دریافت فایل'
            ],
            'deleteFile' => [
                'title' => 'حذف فایل'
            ],
            'sortFiles' => [
                'title' => 'مرتب سازی فایل ها'
            ],
            'addFileGroup' => [
                'title' => 'افزودن گروه'
            ],
            'updateFileGroup' => [
                'title' => 'ویرایش گروه'
            ],
            'getFileGroup' => [
                'title' => 'دریافت گروه'
            ],
            'deleteFileGroup' => [
                'title' => 'حذف گروه'
            ],
            'sortGroups' => [
                'title' => 'مرتب سازی گروه ها'
            ],
            'uploader' => [
                'title' => 'آپلودر'
            ],
            'uploaderViewAllFiles' => [
                'title' => 'مشاهده تمام فایل ها'
            ],
            'deleteImage' => [
                'title' => 'حذف فایل ها'
            ],
            'downloadBeforePublic' => [
                'title' => 'دانلود فایل های قبل از public'
            ]
        ]
    ],

    'posts' => [
        'title' => 'مطالب',
        'permissions' => [
            'canSetAllPost' => [
                'title' => 'اختصاص تمام مطالب'
            ],
            'canViewAllPost' => [
                'title' => 'مشاهده تمام مطالب'
            ],
            'canSetAllPostBoxes' => [
                'title' => 'اختصاص تمام باکس های مطالب'
            ],
            'createPost' => [
                'title' => 'افزودن مطلب'
            ],
            'updatePost' => [
                'title' => 'ویرایش مطلب'
            ],
            'deletePost' => [
                'title' => 'حذف مطلب'
            ],
            'finalStatus' => [
                'title' => 'تغییر وضعیت نهایی'
            ],
            'translatePosts' => [
                'title' => 'ترجمه مطالب'
            ],
            'categories' => [
                'title' => 'دسته بندی ها'
            ],
            'canSetAllCategories' => [
                'title' => 'اختصاص تمام دسته ها'
            ],
            'canViewAllCategories' => [
                'title' => 'مشاهده تمام دسته ها'
            ],
            'showCategory' => [
                'title' => 'مشاهده دسته‌ها'
            ],
            'createCategory' => [
                'title' => 'افزودن دسته‌ها'
            ],
            'updateCategory' => [
                'title' => 'ویرایش دسته‌ها'
            ],
            'deleteCategory' => [
                'title' => 'حذف دسته‌ها'
            ],
            'tags' => [
                'title' => 'برچسب ها'
            ],
            'showTag' => [
                'title' => 'مشاهده برچسب'
            ],
            'createTag' => [
                'title' => 'افزودن برچسب'
            ],
            'updateTag' => [
                'title' => 'ویرایش برچسب'
            ],
            'deleteTag' => [
                'title' => 'حذف برچسب'
            ],
            'comments' => [
                'title' => 'نظرات'
            ],
            'showComment' => [
                'title' => 'مشاهده نظر'
            ],
            'createComment' => [
                'title' => 'افزودن نظر'
            ],
            'updateComment' => [
                'title' => 'ویرایش نظر',
            ],
            'deleteComment' => [
                'title' => 'حذف نظر',
            ],
        ]
    ],

    'attributes' => [
        'title' => 'ویژگی‌ها',
        'permissions' => [
            'showAttribute' => [
                'title' => 'مشاهده ویژگی‌'
            ],
            'createAttribute' => [
                'title' => 'افزودن ویژگی‌'
            ],
            'updateAttribute' => [
                'title' => 'ویرایش ویژگی‌'
            ],
            'deleteAttribute' => [
                'title' => 'حذف ویژگی‌'
            ],
            'translateAttributes' => [
                'title' => 'ترجمه ویژگی ها'
            ],
            'listAttributeKey' => [
                'title' => 'لیست کلیدهای ویژگی'
            ],
            'showAttributeKey' => [
                'title' => 'مشاهده کلید ویژگی'
            ],
            'createAttributeKey' => [
                'title' => 'افزودن کلید ویژگی'
            ],
            'updateAttributeKey' => [
                'title' => 'ویرایش کلید ویژگی'
            ],
            'deleteAttributeKey' => [
                'title' => 'حذف کلید ویژگی'
            ],
            'translateAttributeKeys' => [
                'title' => 'ترجمه کلید ها'
            ],
            'listAttributeValue' => [
                'title' => 'لیست مقادیر ویژگی'
            ],
            'showAttributeValue' => [
                'title' => 'مشاهده مقدار ویژگی'
            ],
            'createAttributeValue' => [
                'title' => 'افزودن مقدار ویژگی'
            ],
            'updateAttributeValue' => [
                'title' => 'ویرایش مقدار ویژگی'
            ],
            'deleteAttributeValue' => [
                'title' => 'حذف مقدار ویژگی'
            ],
            'translateAttributeValues' => [
                'title' => 'ترجمه مقادیر'
            ],
            'postSearchAttribute' => [
                'title' => 'فیلتر جستجو'
            ],
        ]
    ],

    'menus' => [
        'title' => 'فهرست ها',
        'permissions' => [
            'showMenu' => [
                'title' => 'مشاهده فهرست'
            ],
            'createMenu' => [
                'title' => 'افزودن فهرست'
            ],
            'updateMenu' => [
                'title' => 'ویرایش فهرست',
            ],
            'deleteMenu' => [
                'title' => 'حذف فهرست',
            ],
        ]
    ],

    'reports' => [
        'title' => 'گزارشات',
        'permissions' => [
            'searchReport' => [
                'title' => 'گزارش جستجو'
            ]
        ]
    ],

    'financial' => [
        'title' => 'مالی',
        'permissions' => [
            'transactions' => [
                'title' => 'تراکنش ها'
            ],
            'showTransaction' => [
                'title' => 'مشاهده تراکنش'
            ],
            'createTransaction' => [
                'title' => 'افزودن تراکنش'
            ],
            'updateTransaction' => [
                'title' => 'ویرایش تراکنش',
            ],
            'deleteTransaction' => [
                'title' => 'حذف تراکنش',
            ],
            'orders' => [
                'title' => 'سفارش ها'
            ],
            'showOrder' => [
                'title' => 'مشاهده سفارش'
            ],
            'createOrder' => [
                'title' => 'افزودن سفارش'
            ],
            'updateOrder' => [
                'title' => 'ویرایش سفارش',
            ],
            'deleteOrder' => [
                'title' => 'حذف سفارش',
            ],
        ]
    ],

    'support' => [
        'title' => 'پشتیبانی',
        'permissions' => [
            'tickets' => [
                'title' => 'تیکت ها'
            ],
            'showTicket' => [
                'title' => 'مشاهده تیکت'
            ],
            'createTicket' => [
                'title' => 'افزودن تیکت'
            ],
            'updateTicket' => [
                'title' => 'ویرایش تیکت',
            ],
            'deleteTicket' => [
                'title' => 'حذف تیکت',
            ],
        ]
    ],

    'location' => [
        'title' => 'موقعیت',
        'permissions' => [
            'canSetAllCountries' => [
                'title' => 'اختصاص تمام کشورها'
            ],
            'canSetAllProvinces' => [
                'title' => 'اختصاص تمام استان‌ها'
            ],
            'canSetAllCities' => [
                'title' => 'اختصاص تمام شهرستان‌ها'
            ],
            'canSetAllTowns' => [
                'title' => 'اختصاص تمام شهرها'
            ],
        ]
    ],

    'settings' => [
        'title' => 'تنظیمات',
        'permissions' => [
            'generalSetting' => [
                'title' => 'تنظیمات عمومی'
            ],
            'imagesSetting' => [
                'title' => 'تنظیمات تصاویر'
            ],
            'themeValuesSetting' => [
                'title' => 'تنظیمات قالب'
            ],
            'protocolSetting' => [
                'title' => 'تنظیمات پروتکل'
            ],
            'languageSetting' => [
                'title' => 'تنظیمات زبان'
            ]
        ]
    ],

];
