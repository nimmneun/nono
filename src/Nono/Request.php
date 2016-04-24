<?php

namespace Nono;

class Request
{
    /**
     * @return string
     */
    public function uri()
    {
        return urldecode($this->plainUri());
    }

    /**
     * @return string
     */
    public function url()
    {
        return $this->scheme() . '://' . $this->host() . $this->plainUri();
    }

    /**
     * @return string
     */
    public function plainUri()
    {
        return explode('?', $this->server('REQUEST_URI'))[0];
    }

    /**
     * @return string
     */
    public function method()
    {
        return $this->server('REQUEST_METHOD');
    }

    /**
     * @return string
     */
    public function scheme()
    {
        return $this->server('REQUEST_SCHEME');
    }

    /**
     * @return string
     */
    public function host()
    {
        return $this->server('HTTP_HOST');
    }

    /**
     * @return float
     */
    public function timeFloat()
    {
        return $this->server('REQUEST_TIME_FLOAT');
    }

    /**
     * @param string $key
     * @return string|null
     */
    public function server($key)
    {
        return isset($_SERVER[$key]) ? $_SERVER[$key] : null;
    }

    /**
     * @param string $key
     * @return string|null
     */
    public function query($key)
    {
        return isset($_REQUEST[$key]) ? $_REQUEST[$key] : null;
    }

    /**
     * @param string $key
     * @return string|null
     */
    public function session($key)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }
}