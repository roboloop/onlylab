<?php

namespace App\Bag;

class Bag implements \ArrayAccess
{
    protected $parameters;

    public function __construct(array $parameters = [])
    {
        $this->parameters = $parameters;
    }

    public function all()
    {
        return $this->parameters;
    }

    public function keys()
    {
        return array_keys($this->parameters);
    }

    public function replace(array $parameters = [])
    {
        $this->parameters = $parameters;
    }

    public function add(array $parameters = [])
    {
        $this->parameters = array_replace($this->parameters, $parameters);
    }

    public function get($key, $default = null)
    {
        return array_key_exists($key, $this->parameters) ? $this->parameters[$key] : $default;
    }

    public function set($key, $value)
    {
        $this->parameters[$key] = $value;
    }

    public function has($key)
    {
        return array_key_exists($key, $this->parameters);
    }

    public function remove($key)
    {
        unset($this->parameters[$key]);
    }

    public function offsetExists($offset)
    {
        return isset($this->parameters[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->parameters[$offset]) ? $this->parameters[$offset] : null;
    }

    public function offsetSet($offset, $value)
    {
        if (null === $offset)
            $this->parameters[] = $value;
        else
            $this->parameters[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->parameters[$offset]);
    }
}
