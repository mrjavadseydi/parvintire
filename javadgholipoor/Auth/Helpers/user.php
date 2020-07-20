<?php

use LaraBase\Auth\Models\User;

function getUser($userId) {
    $userKey = 'user_' . $userId;
    if (!hasCache($userKey)) {
        $user = User::whereId($userId)->first();
        setCache($userKey, $user, 9999);
    }
    return $user;
}
