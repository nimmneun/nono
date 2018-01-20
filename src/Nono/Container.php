<?php

namespace Nono;

/**
 * Simple di container without the fancy.
 */
class Container extends \ArrayObject
{
    /**
     * @param string     $key
     * @param mixed|null $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return isset($this[$key]) ? $this[$key] : $default;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return isset($this[$key]);
    }
}