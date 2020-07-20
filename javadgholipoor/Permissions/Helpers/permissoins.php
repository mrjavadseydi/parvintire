<?php

function can($permission, $disableAbort = false) {

    if (auth()->check()) {
        $user = auth()->user();

        if ($user->can($permission))
            return true;

        if ($user->hasMeta('developer')) {
            $devPerms = [
                'permissions',
                'createPermission',
                'editPermission',
                'deletePermission',
                'roles',
                'createRole',
                'editRole',
                'deleteRole'
            ];
            if (in_array($permission, $devPerms))
                return true;
        }
    }

    if ($disableAbort)
        return false;

    return abort(401);

}
