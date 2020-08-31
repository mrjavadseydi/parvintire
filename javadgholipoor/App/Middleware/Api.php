<?php

namespace LaraBase\App\Middleware;

use Closure;
use Firebase\JWT\JWT;
use LaraBase\Auth\Models\User;
use Mockery\Exception;

class Api
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function handle($request, Closure $next, $guard = null)
    {
        return $this->$guard($request, $next);
    }

    public function auth($request, $next)
    {
        $message = 'unauthorized';
        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            try {
                $authorization = str_replace('Bearer ' , '', $_SERVER['HTTP_AUTHORIZATION']);
                $auth = JWT::decode($authorization, 'apiAuth', ['HS256']);
                $user = User::find($auth->userId);
                if (strtotime('now') <= $auth->expiredAt) {
                    $request->request->add(['user' => $user]);
                    return $next($request);
                } else {
                    $message = 'token expired';
                }
            } catch (\DomainException $e) {
            }
        }
        if (auth()->check()) {
            $request->request->add(['user' => User::find(auth()->id())]);
            return $next($request);
        }
        return response()->json([
            'status' => 'error',
            'message' => $message
        ]);
    }

}
