<?php

namespace App\Http\Middleware;

use Closure;
use Cviebrock\EloquentSluggable\Tests\Models\Post;

class HttpProtocol
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

        if (!env('SERVER')) {
            return $next($request);
        }

        if (checkUsersTable()) {

            if (hasCache('httpProtocol'))
                $httpProtocol = getCache('httpProtocol');
            else {
                $httpProtocol = getOption('httpProtocol');
                setCache('httpProtocol', $httpProtocol);
            }

            if ($httpProtocol != null) {
                $httpProtocol = json_decode($httpProtocol, true);
                $serverName = str_replace('/', '', $request->server->get('SERVER_NAME'));
                $serverNameParts = explode('.', $serverName);
                $lastIndex = count($serverNameParts) - 1;
                $domain = $serverNameParts[$lastIndex];
                $http = $httpProtocol[$domain] ?? null;

                $urlParts = [];

                if ($http != null) {

                    $redirect = false;

                    $urlParts[0] = 'http://';
                    if ($http['http'] == 'toHttps') {
                        $urlParts[0] = 'https://';
                        if (!$request->secure()) {
                            $redirect = true;
                        }
                    }

                    $urlParts[1] = '';
                    if ($http['www'] == 'toWWW') {
                        if ($serverNameParts[0] != 'www') {
                            $redirect = true;
                            $urlParts[1] = 'www.';
                        }
                    } else {
                        if ($serverNameParts[0] == 'www') {
                            $redirect = true;
                        }
                    }

                    if ($serverNameParts[0] == 'www') {
                        unset($serverNameParts[0]);
                    }

                    if ($domain == $http['to']) {
                        $urlParts[2] = implode('.', $serverNameParts);
                    } else {
                        unset($serverNameParts[$lastIndex]);
                        $urlParts[2] = implode('.', $serverNameParts);
                        $urlParts[3] = '.' . $http['to'];
                        $redirect = true;
                    }

                    $requestUri = $request->getRequestUri();
                    if (substr($requestUri, 0, 4) != '/api') {
                        $urlParts[] = $requestUri;
                        if ($redirect) {
                            return redirect(implode('', $urlParts));
                        }
                    }

                }

            }
        }

        return $next($request);

    }
}
