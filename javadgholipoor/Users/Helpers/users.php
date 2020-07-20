<?php

use LaraBase\Users\Users;

function users() {
    $users = new Users();
    return $users->manager();
}

function usersPaginationCount() {
    return Users::paginationCount();
}

function getUserName($userId) {
    $user = \LaraBase\Auth\Models\User::find($userId);
    if ($user) {
        return $user->name();
    }
    return '-';
}
