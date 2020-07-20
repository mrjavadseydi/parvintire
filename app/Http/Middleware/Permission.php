<?php

namespace App\Http\Middleware;

use Closure;

class Permission
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
        if (auth()->check())
            if (!auth()->user()->hasMeta(config('optionsConfig.dev')))
                return abort(401);
        
        return $next($request);
    }
}
