<?php

namespace LaraBase\App\Middleware;

use Closure;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (auth()->check()) {
            $abort = true;
            $user  = auth()->user();

            if ($user->can('roles')) {
                $abort = false;
            } else {
                if ($user->hasMeta(config('optionsConfig.dev'))) {
                    $abort = false;
                }
            }

            if ($abort) {
                return abort(401);
            }
        }

        return $next($request);
    }
}
