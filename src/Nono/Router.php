<?php

declare(strict_types=1);

namespace nimmneun\Nono;

use Exception;

/**
 * Relatively fast regex router. Placeholders in routes like e.g. {username}
 * are replaced to match anything but a slash. A route like /user/{id}/
 * would end up as the regex /user/([^/]+)/.
 */
class Router
{
    protected array $routes = [];

    /**
     * Add a route aimed at a specific http verb.
     *
     * @param string $verb
     * @param string $route
     * @param callable|string[] $action
     */
    public function add(string $verb, string $route, callable|array $action): void
    {
        $this->routes[strtoupper($verb)][] = [
            'route' => $this->pattern($route),
            'action' => $action,
        ];
    }

    /**
     * Route a given http verb and uri.
     *
     * @param string $verb
     * @param string $uri
     * @return array
     * @throws Exception
     */
    public function route(string $verb, string $uri): array
    {
        foreach (array_chunk($this->routes(strtoupper($verb)), 20) as $routes) {
            $match = $this->match($uri, $routes);
            if (!empty($match)) {
                return $match;
            }
        }

        throw new Exception('Route ' . $uri . ' not found');
    }

    protected function pattern(string $str): string
    {
        return preg_replace('~\{([a-zA-Z0-9]+)}~', '([^/]+)', $str);
    }

    /**
     * Return the array of routes for the given http verb.
     */
    protected function routes(string $verb): array
    {
        return $this->routes[$verb] ?? [];
    }

    /**
     * Match a uri against several routes at once and return the associated
     * action and any parameters matched in the uri.
     * The returned action is the callback or class::method you've set.
     */
    protected function match(string $uri, array $routes): array
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
     * Grouped patterns thanks to nikic's blog post - you rock =).
     * http://nikic.github.io/2014/02/18/Fast-request-routing-using-regular-expressions.html
     * The empty groups () are basically abused as a counter, hence
     * they mark a given route.
     */
    protected function combine(array $routes): string
    {
        $str = $mark = '';
        foreach ($routes as $data) {
            $str .= '|' . $data['route'] . $mark;
            $mark .= '()';
        }

        return '~^(?' . $str . ')$~x';
    }
}
