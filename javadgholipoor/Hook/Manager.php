<?php
namespace LaraBase\Hook;

/**
 * Class Manager
 * @package Cafesource\Hook
 */
class Manager
{
    protected $events = [];

    /**
     * @param $callback
     * @param array $values
     * @return Callback
     */
    protected function createCallbackObject($callback, $values = [])
    {
        return new Callback($callback, $values);
    }

    /**
     * Add hook
     *
     * @param $name
     * @param $callback
     * @param int $priority
     * @param int $acceptedArgs
     * @return bool
     */
    public function add($name, $callback, $priority = 10, $acceptedArgs = 1)
    {
        $this->events[$name][$priority][] = [
            'function' => $callback,
            'acceptedArgs' => $acceptedArgs,
            // 'type' => $this->callbackType($callback)
        ];

        return true;
    }

    /**
     * Run hook
     *
     * @param string $name
     * @param string|array|object $value
     * @return string|string|object
     */
    public function do($name, $value = null)
    {
        
        # Checking for has hook
        if (!$this->has($name)) {
            return $value;
        }

        $countArgs = !is_null($value) ? count($value) : 1;
        $this->sortPriority($name);
        foreach ($this->events[$name] as $priority => $callback) {
            foreach ($callback as $method) {
                # Without args
                if (is_callable($method['function'])) {
                    $value = $this->createCallbackObject($method['function'], $value)->call();
                } else if ($method['acceptedArgs'] == 0) {
                    $value = call_user_func_array($method['function'], []);
                } else if ($method['acceptedArgs'] >= $countArgs) {
                    $value = call_user_func_array($method['function'], $value);
                } else if (is_string($method) && function_exists($method['function'])) {
                    $value = call_user_func($method['function'], $value);
                } else {
                    $value = call_user_func_array($method['function'], array_slice($value, 0, (int)$method['acceptedArgs']));
                }
            }
        }
    
        return $value;
    }

    private function sortPriority($name)
    {
        ksort($this->events[$name]);
    }

    public function has($name)
    {
        return isset($this->events[$name]);
    }

    public function remove($name)
    {
        unset($this->events[$name]);
    }

    public function listing()
    {
        return $this->events;
    }
}
