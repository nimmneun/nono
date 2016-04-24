<?php
/**
 * @author neun
 * @since  2016-04-24
 */

namespace Nono;

class Router
{
    private $routes;

    public function __construct(array $routes = [])
    {
        $this->routes = $routes;
    }

    public function route(Request $request)
    {
        foreach ($this->routes as $route => $action) {
            if ($params = $this->match($route, $request)) {
                return $this->call($action, $params);
            }
        }

        throw new \Exception("Router not found!");
    }

    private function match($route, Request $request)
    {
        if (preg_match($this->pattern($route), $request->uri(), $matches)) {
            $matches[0] = $request;
        }

        return $matches;
    }

    private function pattern($route)
    {
        return '~^' . preg_replace('~(\{[\w]+\})~', '(\w+)', $route) . '/?$~u';
    }

    private function call($action, $params)
    {
        if ($action instanceof \Closure) {
            return $action(...$params);
        }

        if (is_string($action) && is_int(strpos($action, '::'))) {
            list($class, $method) = explode('::', $action);
            if (class_exists($class)) {
                return (new $class(array_shift($params)))->$method(...$params);
            }
        }

        throw new \Exception('Failed to call callable ' . serialize($action));
    }
}