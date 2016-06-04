<?php

namespace Nono;

/**
 * @method string get(string $name)
 * @method string post(string $name)
 * @method string request(string $name)
 * @method string server(string $name)
 * @method string session(string $name)
 * @method array files(string $name)
 */
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
    public function requestTimeFloat()
    {
        return $this->server('REQUEST_TIME_FLOAT');
    }

    /**
     * @return float
     */
    public function elapsedRequestTimeFloat()
    {
        return microtime(1) - $this->server('REQUEST_TIME_FLOAT');
    }

    /**
     * @param string $name
     * @param array $args
     * @return string|array|null
     */
    public function __call($name, $args)
    {
        return isset($GLOBALS['_' . strtoupper($name)][$args[0]])
            ? $GLOBALS['_' . strtoupper($name)][$args[0]]
            : null;
    }
}