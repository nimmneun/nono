<?php

namespace Nono;

class Router
{
    /**
     * @var array
     */
    private $routes;

    /**
     * @var string $verb
     * @var string $route
     * @var \Closure|string $action
     */
    public function add($verb, $route, $action)
    {
        $this->routes[strtoupper($verb)][] = [
            'route' => $this->pattern($route),
            'action' => $action
        ];
    }

    /**
     * @var array $verbs
     * @var string $route
     * @var \Closure|string $action
     */
    public function any(array $verbs, $route, $action)
    {
        foreach ($verbs as $verb) {
            $this->add($verb, $route, $action);
        }
    }

    /**
     * @param string $verb
     * @param string $uri
     * @return \Closure|string
     * @throws \Exception
     */
    public function route($verb, $uri)
    {
        foreach (array_chunk($this->routes($verb), 22) as $routes) {
            $match = $this->match($uri, $routes);
            if (!empty($match)) {
                return $match;
            }
        }

        throw new \Exception('Route ' . $uri . ' not found');
    }

    /**
     * @var string $verb
     * @return array
     */
    private function routes($verb)
    {
        return isset($this->routes[strtoupper($verb)]) ? $this->routes[strtoupper($verb)] : [];
    }

    /**
     * @param string $uri
     * @param array $routes
     * @return array
     */
    private function match($uri, $routes)
    {
        if (1 != preg_match($this->combine($routes), $uri, $m)) {
            return [];
        }

        $params = array_filter($m, function ($v) {
            return 0 < strlen($v);
        });
        $action = $routes[count($m) - count($params)]['action'];

        return [$action, $params];
    }

    /**
     * @var string $verb
     * @return string
     */
    private function pattern($str)
    {
        return preg_replace('~\{([a-zA-Z0-9]+)\}~', '([^/]+)', $str);
    }

    /**
     * @var array $routes
     * @return string
     */
    private function combine($routes)
    {
        $str = $mark = '';
        foreach ($routes as $id => $data) {
            $str .= '|' . $data['route'] . $mark;
            $mark .= '()';
        }

        return '~^(?' . $str . ')$~x';
    }
}
