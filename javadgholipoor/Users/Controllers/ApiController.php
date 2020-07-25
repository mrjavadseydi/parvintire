<?php


namespace LaraBase\Users\Controllers;


use LaraBase\Auth\Models\User;

class ApiController
{

    public function users()
    {
        can('users');
        $users = User::canView()->search()->sort()->paginate(usersPaginationCount());
        return response()->json($users);
    }

    public function user($id)
    {
        can('showUser');
        $user = User::find($id);
        $more = [
        ];
        return response()->json(array_merge($user->toArray(), $more));
    }

}
