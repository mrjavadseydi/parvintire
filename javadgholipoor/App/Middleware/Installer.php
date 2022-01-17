<?php

namespace LaraBase\App\Middleware;

use Closure;

class Installer
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

        $installer = false;
        if (!checkDatabaseConnection()) {
            if (!strpos(url()->current(), '/larabase/installer')) {
                if (!isset($_GET['step'])) {
                    $installer = true;
                }
            }
        } else {
            if (!checkUsersTable() && !checkVisitTable()) {
                if (!strpos(url()->current(), '/larabase/installer')) {
                    if (!isset($_GET['step'])) {
                        $installer = true;
                    }
                }
            }
        }

        if ($installer) {
            return redirect(route('larabase.installer') . '?step=1');
        }

        return $next($request);

    }
}
