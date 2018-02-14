<?php

namespace Nono;

/**
 * Simple request class, which is passed as the first argument to
 * any callable / controller method associated with a route.
 *
 * @method string|mixed get(string $name = null, mixed $default = null)
 * @method string|mixed post(string $name = null, mixed $default = null)
 * @method string|mixed query(string $name = null, mixed $default = null)
 * @method string|mixed request(string $name = null, mixed $default = null)
 * @method string|mixed server(string $name = null, mixed $default = null)
 * @method string|mixed session(string $name = null, mixed $default = null)
 * @method string|mixed cookie(string $name = null, mixed $default = null)
 * @method array|mixed  files(string $name = null, mixed $default = null)
 */
class Request
{
    /**
     * Just triggering the super global and fill the query global.
     */
    public function __construct()
    {
        $_SERVER;
        if (!in_array($this->method(), ['GET', 'POST'])) {
            parse_str(file_get_contents('php://input'), $GLOBALS['_QUERY']);
        }
    }

    /**
     * Return URI with query string.
     *
     * @return string
     */
    public function uriWithQuery()
    {
        return urldecode($this->server('REQUEST_URI'));
    }

    /**
     * Return URI without query string.
     *
     * @return string
     */
    public function uri()
    {
        return explode('?', $this->uriWithQuery())[0];
    }

    /**
     * Return the http verb.
     *
     * @return string
     */
    public function method()
    {
        return $this->server('REQUEST_METHOD');
    }

    /**
     * Return whether the request URL is HTTPS URL or not.
     * Questionable reliability -> depends upon server config.
     *
     * @return string
     */
    public function isHttps()
    {
        return $this->server('SERVER_PORT') == 443
            || strtoupper($this->server('HTTPS')) == 'ON';
    }

    /**
     * Return the hostname.
     *
     * @return string
     */
    public function host()
    {
        return $this->server('HTTP_HOST');
    }

    /**
     * Return the time the request was initiated.
     *
     * @return float
     */
    public function requestTimeFloat()
    {
        return $this->server('REQUEST_TIME_FLOAT');
    }

    /**
     * Return the elapsed time since the request was initiated.
     *
     * @return float
     */
    public function elapsedRequestTimeFloat()
    {
        return microtime(1) - $this->server('REQUEST_TIME_FLOAT');
    }

    /**
     * Allow redirects when headers have already been sent
     * due to sessions or other output.
     *
     * @param string $url
     */
    public function redirect($url)
    {
        echo "<script>location.replace('$url');</script>";
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

