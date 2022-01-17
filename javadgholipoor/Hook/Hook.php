<?php

namespace LaraBase\Hook;

/**
 * Hook Class
 * @package LaraBase\Hook
 *
 * @method bool add() add(string $name, callable $callback, int $priority = 10, int $acceptedArgs = 1)
 * @method string|array|object do() do($name, array|object|string $value = null)
 * @method bool has() has(string $name)
 * @method void remove() remove(string $name)
 * @method array listing() listing()
 *
 * @author Mahdi akbari <mahdi75akbari@gmail.com>
 * @author Javad Gholipoor <apkmaker74@gmail.com>
 */
final class Hook
{
    protected static $manager;
    private static $instance = null;

    /**
     * Hook constructor.
     */
    public function __construct()
    {
        return self::manager();
    }

    /**
     * @return Manager|null
     */
    protected static function manager()
    {
        if (is_null(self::$instance)) {
            self::$instance = new Manager();
        }

        return self::$instance;
    }

    /**
     * Calling to method manager
     *
     * @param $method
     * @param $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return self::manager()->$method(...$parameters);
    }

    /***
     * Calling to static method manager
     *
     * @param $method
     * @param $parameters
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        return static::manager()->$method(...$parameters);
    }
}
