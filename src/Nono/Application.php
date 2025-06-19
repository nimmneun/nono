<?php

declare(strict_types=1);

namespace nimmneun\Nono;

use Closure;
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

    public function get(string $route, Closure|string $action): void
    {
        $this->router->add('GET', $route, $action);
    }

    public function post(string $route, Closure|string $action): void
    {
        $this->router->add('POST', $route, $action);
    }

    public function put(string $route, Closure|string $action): void
    {
        $this->router->add('PUT', $route, $action);
    }

    public function delete(string $route, Closure|string $action): void
    {
        $this->router->add('DELETE', $route, $action);
    }

    /**
     * Add a route for several http verbs e.g. ['PUT', 'POST'].
     *
     * @param string[] $verbs
     * @param string $route
     * @param Closure|string $action
     */
    public function any(array $verbs, string $route, Closure|string $action): void
    {
        $this->router->any($verbs, $route, $action);
    }

    /**
     * Send content to browser/output.
     */
    public function respond(): void
    {
        echo $this->run();
    }

    /**
     * Return response contents.
     */
    public function run(): string
    {
        ob_start();
        try {
            [$action, $params] = $this->router->route(
                $this->request->method(),
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
     * @param Closure|string $action
     * @param array<int,mixed> $params
     * @throws Exception
     */
    protected function call(Closure|string $action, array $params): void
    {
        if ($action instanceof Closure) {
            $action(...$params);
            return;
        }

        if (str_contains($action, '::')) {
            [$class, $method] = explode('::', $action, 2);

            if (class_exists($class) && method_exists($class, $method)) {
                $this->container->make($class)->{$method}(...$params);
                return;
            }

            throw new Exception("Failed to call {$action}");
        }

        throw new Exception('Failed to call callable');
    }

    /**
     * Just echo the message for now.
     *
     * @param Exception $e
     */
    protected function handleException(Exception $e): void
    {
        echo $e->getMessage();
    }
}
