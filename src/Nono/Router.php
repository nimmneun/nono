<?php

namespace Nono;

/**
 * Relatively fast regex router. Placeholders in routes like e.g. {username}
 * are replaced to match anything but a slash. A route like /user/{id}/
 * would end up as the regex /user/([^/]+)/.
 */
class Router
{
    /**
     * @var array
     */
    private $routes;

    /**
     * Add a route aimed at a specific http verb.
     *
     * @var string          $verb
     * @var string          $route
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
     * Add a route for multiple http verbs [POST,PUT,GET,...].
     *
     * @var array           $verbs
     * @var string          $route
     * @var \Closure|string $action
     */
    public function any(array $verbs, $route, $action)
    {
        foreach ($verbs as $verb) {
            $this->add($verb, $route, $action);
        }
    }

    /**
     * Route a given http verb and uri.
     *
     * @param string $verb
     * @param string $uri
     * @return array
     * @throws \Exception
     */
    public function route($verb, $uri)
    {
        foreach (array_chunk($this->routes(strtoupper($verb)), 20) as $routes) {
            $match = $this->match($uri, $routes);
            if (!empty($match)) {
                return $match;
            }
        }

        throw new \Exception('Route ' . $uri . ' not found');
    }

    /**
     * Return the array of routes for the given http verb.
     *
     * @var string $verb
     * @return array
     */
    private function routes($verb)
    {
        return isset($this->routes[$verb]) ? $this->routes[$verb] : [];
    }

    /**
     * Match a uri against several routes at once and return the associated
     * action and any parameters matched in the uri.
     * The returned action is the callback or class::method you've set.
     *
     * @param string $uri
     * @param array  $routes
     * @return array
     */
    private function match($uri, $routes)
    {
        if (!preg_match($this->combine($routes), $uri, $m)) {
            return [];
        }

        $params = array_filter($m, function ($v) {
            return 0 < strlen($v);
        });

        $marker = count($m) - count($params);
        $action = $routes[$marker]['action'];

        return [$action, $params];
    }

    /**
     * Replace placeholders like {id} with a regex.
     *
     * @var string $str
     * @return string
     */
    private function pattern($str)
    {
        return preg_replace('~\{([a-zA-Z0-9]+)\}~', '([^/]+)', $str);
    }

    /**
     * Grouped patterns thanks to nikic's blog post - you rock =).
     * http://nikic.github.io/2014/02/18/Fast-request-routing-using-regular-expressions.html
     * The empty groups () are basically abused as a counter, hence
     * they mark a given route.
     *
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
