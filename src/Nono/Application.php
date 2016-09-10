<?php

namespace Nono;

/**
 * Class Application - because everyone and their grandmother
 * rolls his own framework. Since I'm lazy this is rather minimal. =)
 *
 * @package Nono
 */
class Application
{
    /**
     * @var Router
     */
    private $router;

    /**
     * @var Request
     */
    private $request;

    /**
     * @param Router  $router
     * @param Request $request
     */
    public function __construct(Router $router = null, Request $request = null)
    {
        $this->router = $router ?: new Router();
        $this->request = $request ?: new Request();
    }

    /**
     * @param string          $route
     * @param \Closure|string $action
     */
    public function get($route, $action)
    {
        $this->router->add('GET', $route, $action);
    }

    /**
     * @param string          $route
     * @param \Closure|string $action
     */
    public function post($route, $action)
    {
        $this->router->add('POST', $route, $action);
    }

    /**
     * @param string          $route
     * @param \Closure|string $action
     */
    public function put($route, $action)
    {
        $this->router->add('PUT', $route, $action);
    }

    /**
     * @param string          $route
     * @param \Closure|string $action
     */
    public function delete($route, $action)
    {
        $this->router->add('DELETE', $route, $action);
    }

    /**
     * @param array           $verbs
     * @param string          $route
     * @param \Closure|string $action
     */
    public function any(array $verbs, $route, $action)
    {
        $this->router->any($verbs, $route, $action);
    }

    /**
     * @param       $action
     * @param array $params
     * @throws \Exception
     */
    private function call($action, $params)
    {
        if ($action instanceof \Closure) {
            $action(...$params);
        } elseif (is_string($action) && is_int(strpos($action, '::'))) {
            list($class, $method) = explode('::', $action);
            if (class_exists($class) && method_exists($class, $method)) {
                (new $class(array_shift($params)))->$method(...$params);
            } else {
                throw new \Exception("Failed to call {$action}");
            }
        } else {
            throw new \Exception('Failed to call callable');
        }
    }

    /**
     * Send content to browser.
     */
    public function respond()
    {
        echo $this->run();
    }

    /**
     * Return response contents.
     *
     * @return string
     */
    public function run()
    {
        ob_start();
        try {
            list($action, $params) = $this->router->route(
                $this->request->method(), $this->request->uri()
            );

            $params[0] = $this->request;
            $this->call($action, $params);
        } catch (\Exception $e) {
            $this->handleException($e);
        }

        return ob_get_clean();
    }

    /**
     * Just echo the message for now.
     *
     * @param \Exception $e
     */
    private function handleException(\Exception $e)
    {
        echo PHP_EOL . $e->getMessage() . PHP_EOL;
    }
}

