<?php

namespace Nono;

class Request
{
    public function uri()
    {
        return urldecode($this->plainUri());
    }

    public function url()
    {
        return $this->scheme() . '://' . $this->host() . $this->plainUri();
    }

    public function plainUri()
    {
        return explode('?', $this->server('REQUEST_URI'))[0];
    }

    public function method()
    {
        return $this->server('REQUEST_METHOD');
    }

    public function scheme()
    {
        return $this->server('REQUEST_SCHEME');
    }

    public function host()
    {
        return $this->server('HTTP_HOST');
    }

    public function timeFloat()
    {
        return $this->server('REQUEST_TIME_FLOAT');
    }

    public function server($key)
    {
        return $this->read('_SERVER', $key);
    }

    public function query($key)
    {
        return $this->read('_REQUEST', $key);
    }

    private function read($global, $key)
    {
        return isset($GLOBALS[$global][$key]) ? $GLOBALS[$global][$key] : null;
    }
}