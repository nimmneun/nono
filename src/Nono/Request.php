<?php

namespace Nono;

class Request
{
    public function __construct($uri = null)
    {
        if ($uri) {
            $_SERVER['REQUEST_URI'] = $uri;
        }
    }

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
    public function requestTimeFloat()
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
    public function request($key)
    {
        return isset($_REQUEST[$key]) ? $_REQUEST[$key] : null;
    }

    /**
     * @param string $key
     * @return string|null
     */
    public function get($key)
    {
        return isset($_GET[$key]) ? $_GET[$key] : null;
    }

    /**
     * @param string $key
     * @return string|null
     */
    public function post($key)
    {
        return isset($_POST[$key]) ? $_POST[$key] : null;
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