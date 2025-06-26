<?php

declare(strict_types=1);

namespace nimmneun\Nono;

use Exception;

/**
 * Because everyone and their grandmother rolls their own framework.
 * Since I'm lazy this is rather minimal, but does to job. =)
 */
class Application
{
    public function __construct(
        protected ?Router $router = null,
        protected ?Request $request = null,
        protected ?Container $container = null,
    ) {
        $this->router = $router ?? new Router();
        $this->request = $request ?? new Request();
        $this->container = $container ?? new Container();
    }

    public function container(): Container
    {
        return $this->container;
    }

    /**
     * @param string $route
     * @param callable|string[] $action
     */
    public function get(string $route, callable|array $action): void
    {
        $this->router->add('GET', $route, $action);
    }

    /**
     * @param string $route
     * @param callable|string[] $action
     */
    public function post(string $route, callable|array $action): void
    {
        $this->router->add('POST', $route, $action);
    }

    /**
     * @param string $route
     * @param callable|string[] $action
     */
    public function put(string $route, callable|array $action): void
    {
        $this->router->add('PUT', $route, $action);
    }

    /**
     * @param string $route
     * @param callable|string[] $action
     */
    public function delete(string $route, callable|array $action): void
    {
        $this->router->add('DELETE', $route, $action);
    }

    /**
     * Add a route for several http verbs e.g. ['PUT', 'POST'].
     */
    public function any(array $verbs, string $route, callable|array $action): void
    {
        foreach ($verbs as $verb) {
            $this->router->add($verb, $route, $action);
        }
    }

    public function respond(): void
    {
        echo $this->run();
    }

    public function run(): string
    {
        ob_start();
        try {
            [$action, $params] = $this->router->route(
                $this->request->verb(),
                $this->request->uri(),
            );

            $params[0] = $this->request;
            $this->call($action, $params);
        } catch (Exception $e) {
            $this->handleException($e);
        }

        return ob_get_clean();
    }

    /**
     * @param callable|string[] $action
     * @param array<int,mixed> $params
     * @throws Exception
     */
    protected function call(callable|array $action, array $params): void
    {
        if (is_callable($action)) {
            $action(...$params);
            return;
        }

        [$class, $method] = $action;

        if (class_exists($class) && method_exists($class, $method)) {
            $this->container->make($class)->{$method}(...$params);
            return;
        }

        throw new Exception(sprintf("Failed to call action %s", json_encode($action)));
    }

    protected function handleException(Exception $e): void
    {
        echo $e->getMessage();
    }
}
