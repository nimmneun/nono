<?php

namespace Nono;

class Router
{
    /**
     * @var array
     */
    private $routes;

    /**
     * @param array $routes
     */
    public function __construct(array $routes = [])
    {
        $this->routes = $routes;
    }

    /**
     * @param Request $request
     * @return null
     * @throws \Exception
     */
    public function route(Request $request)
    {
        foreach ($this->routes as $route => $action) {
            if ($params = $this->match($route, $request)) {
                return $this->call($action, $params);
            }
        }

        throw new \Exception('Route ' . $request->uri() . ' not found!');
    }

    /**
     * @param string $route
     * @param Request $request
     * @return array
     */
    private function match($route, Request $request)
    {
        $matches = [];
        if (preg_match($this->pattern($route), $request->uri(), $matches)) {
            $matches[0] = $request;
        }

        return $matches;
    }

    /**
     * @param string $route
     * @return string
     */
    private function pattern($route)
    {
        return '~^' . preg_replace('~(\{[\w]+\})~', '(\w+)', $route) . '/?$~u';
    }

    /**
     * @param $action
     * @param array $params
     * @return null
     * @throws \Exception
     */
    private function call($action, $params)
    {
        if ($action instanceof \Closure) {
            return $action(...$params);
        }

        if (is_string($action) && is_int(strpos($action, '::'))) {
            list($class, $method) = explode('::', $action);
            if (class_exists($class)) {
                $request = array_shift($params);
                return (new $class($request))->$method(...$params);
            }
        }

        throw new \Exception('Failed to call callable ' . serialize($action));
    }
}