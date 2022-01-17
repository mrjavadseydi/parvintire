<?php

namespace LaraBase\Hook;

class Callback
{
    protected $events;
    protected $parameters = [];

    /**
     * Callback constructor
     */
    public function __construct($function, $args = [])
    {
        $this->setCallback($function, $args);
    }

    /**
     * Set callback name and args
     * @param array|string $function
     * @param array|string $args
     * @return void
     */
    public function setCallback($function, $args)
    {
        $this->function = $function;
        $this->parameters = $args;
    }

    /**
     * Calling to method and function
     * @param array $args
     * @return array|string|object
     */
    public function call($args = [])
    {
        $args = $args ?: $this->parameters;
        return call_user_func_array($this->function, [$args]);
    }
}
