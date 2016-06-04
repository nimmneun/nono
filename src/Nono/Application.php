<?php

namespace Nono;

class Application
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var string
     */
    private $content;

    /**
     * @param string $rootDir
     * @param Request $request
     * @param Router $router
     */
    public function __construct($rootDir, Request $request, Router $router)
    {
        define('APP_ROOT', realpath($rootDir));
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
     * @param bool $rerun
     */
    public function respond($rerun = false)
    {
        if ($this->content && !$rerun) {
            echo $this->content;
        } else {
            echo $this->run();
        }
    }
}