<?php

namespace Core;

class Container
{
    private $services = [];
    private $instances = [];
    public function register($name, callable $callable)
    {
        $this->services[$name] = $callable;
    }
    public function get($name)
    {
        if (!isset($this->instances[$name])) {
            if (!isset($this->services[$name])) {
                throw new \Exception("Service {$name} not found");
            }
            $this->instances[$name] = $this->services[$name]($this);
        }
        return $this->instances[$name];
    }
}
