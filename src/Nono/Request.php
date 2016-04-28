<?php

namespace Nono;

class Request
{
    /**
     * @param string $url
     */
    public function __construct($url = null)
    {
        if ($url) {
            $this->overrideRequest(parse_url($url));
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

    /**
     * @param array $parts
     */
    private function overrideRequest(array $parts)
    {
        $_SERVER['REQUEST_SCHEME'] = isset($parts['scheme']) ? $parts['scheme'] : null;
        $_SERVER['HTTP_HOST'] = isset($parts['host']) ? $parts['host'] : null;
        $_SERVER['REQUEST_URI'] = isset($parts['path']) ? $parts['path'] : null;
        $_SERVER['QUERY_STRING'] = isset($parts['query']) ? $parts['query'] : null;
        $_SERVER['REQUEST_TIME_FLOAT'] = microtime(1);

        preg_match_all('~([^&=]+)=([^&=]+)~', $_SERVER['QUERY_STRING'], $m);
        $_REQUEST = $_GET = $_POST = array_combine($m[1], $m[2]);
    }
}