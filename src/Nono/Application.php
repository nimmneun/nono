<?php

namespace Nono;

class Application
{
    private $request;
    private $router;
    private $content;

    /**
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

    /**
     * @return string
     * @throws \Exception
     */
    public function run()
    {
        ob_start();
        $this->router->route($this->request);
        return $this->content = ob_get_clean();
    }

    /**
     * @return void
     */
    public function respond()
    {
        if ($this->content) {
            echo $this->content;
        } else {
            echo $this->run();
        }
    }
}