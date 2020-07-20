<?php

function courseTypes() {
    return [
        'free' => [
            'title' => 'رایگان'
        ],
        'share' => [
            'title' => 'اشتراکی'
        ],
        'cash' => [
            'title' => 'نقدی'
        ]
    ];
}

function courseStatus() {
    return [
        'completing' => [
            'title' => 'درحال تکمیل'
        ],
        'complete' => [
            'title' => 'تکمیل شده'
        ]
    ];
}


function teachers() {

    $permission = \LaraBase\Permissions\Models\Permission::where('name', 'teacher')->first();

    if ($permission == null)
        return [];

    return $permission->users();

}
