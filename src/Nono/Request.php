<?php

namespace Nono;

/**
 * Simple request class which is passed as the first argument to
 * any callable / controller method associated with a route.
 *
 * @method string|mixed get(string $name = null, mixed $default = null)
 * @method string|mixed post(string $name = null, mixed $default = null)
 * @method string|mixed request(string $name = null, mixed $default = null)
 * @method string|mixed server(string $name = null, mixed $default = null)
 * @method string|mixed session(string $name = null, mixed $default = null)
 * @method string|mixed cookie(string $name = null, mixed $default = null)
 * @method array|mixed  files(string $name = null, mixed $default = null)
 */
class Request
{
    /**
     * Return URI with query string.
     *
     * @return string
     */
    public function uri()
    {
        return urldecode($this->plainUri());
    }

    /**
     * Return URI without query string.
     *
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
    public function isHttps()
    {
        return $this->server('SERVER_PORT') == 443
            || strtoupper($this->server('HTTPS')) == 'ON';
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
     * Magic method to access super globals with optional default argument.
     * Calling without arguments e.g. $request->server() will simply
     * return the entire _SERVER global.
     *
     * @param string $name
     * @param array  $args
     * @return mixed
     */
    public function __call($name, $args)
    {
        $global = $GLOBALS['_' . strtoupper($name)];
        $default = isset($args[1]) ? $args[1] : null;

        if (!count($args)) {
            return $global;
        } elseif (isset($global[$args[0]])) {
            return $global[$args[0]];
        } else {
            return $default;
        }
    }
}

