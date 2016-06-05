<?php

namespace Nono;

/**
 * @method void get(string $route, string|Callable $action)
 * @method void post(string $route, string|Callable $action)
 * @method void put(string $route, string|Callable $action)
 * @method void patch(string $route, string|Callable $action)
 * @method void delete(string $route, string|Callable $action)
 * @method void any(string $route, string|Callable $action)
 */
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
        foreach ($this->routes($request->method()) as $route => $action) {
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
        if (substr_count($route, '/') == substr_count($request->uri(), '/')) {
            if (preg_match($this->pattern($route), $request->uri(), $matches)) {
                $matches[0] = $request;
            }
        }

        return $matches;
    }

    /**
     * @param string $route
     * @return string
     */
    private function pattern($route)
    {
        return '~^' . preg_replace('~(\{[\w]+\})~', '([^/]+)', $route) . '/?$~u';
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

        if (is_string($action) && false !== strpos($action, '::')) {
            list($class, $method) = explode('::', $action);
            if (class_exists($class) && method_exists($class, $method)) {
                return (new $class(array_shift($params)))->$method(...$params);
            }
        }

        throw new \Exception('Failed to call callable');
    }

    /**
     * @param string $verb
     * @return array
     */
    private function routes($verb)
    {
        return isset($this->routes[$verb]) ? $this->routes[$verb] : [];
    }

    /**
     * @param string $name
     * @param array $args
     */
    public function __call($name, $args)
    {
        if ($name === 'any') {
            foreach (['GET', 'POST', 'PUT', 'PATCH', 'DELETE'] as $verb) {
                $this->routes[$verb][$args[0]] = $args[1];
            }
        } else {
            $this->routes[strtoupper($name)][$args[0]] = $args[1];
        }
    }
}
