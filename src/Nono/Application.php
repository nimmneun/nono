<?php

namespace Nono;

class Application
{
    private $request;
    private $router;

    /**
     * Application constructor.
     * @param Request $request
     * @param Router $router
     */
    public function __construct(Request $request, Router $router)
    {
        $this->router = $router;
        $this->request = $request;
    }

    /**
     * @return Request
     */
    public function request()
    {
        return $this->request;
    }

    /**
     * @return Router
     */
    public function router()
    {
        return $this->router;
    }

    public function run()
    {
        return $this->router->route($this->request);
    }
}