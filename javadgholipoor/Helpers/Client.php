<?php
namespace Larabase\Helpers;

class Client
{
    /**
     * Get client ip
     * @return string|float
     */
    public static function ip()
    {
        if (getenv('HTTP_CLIENT_IP'))
            return getenv('HTTP_CLIENT_IP');

        if (getenv('HTTP_X_FORWARDED_FOR'))
            return getenv('HTTP_X_FORWARDED_FOR');

        if (getenv('HTTP_X_FORWARDED'))
            return getenv('HTTP_X_FORWARDED');

        if (getenv('HTTP_FORWARDED_FOR'))
            return getenv('HTTP_FORWARDED_FOR');

        if (getenv('HTTP_FORWARDED'))
            return getenv('HTTP_FORWARDED');

        if (getenv('REMOTE_ADDR'))
            return getenv('REMOTE_ADDR');

        return 'UNKNOWN';
    }
}
