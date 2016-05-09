<?php

namespace Nono;

class Application
{
    /**
     * Application root directory e.g. /var/domain.com/
     *
     * @var string
     */
    private $rootDir;

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
        define('APP_ROOT', rtrim($rootDir, '/\\'));
        $this->router = $router;
        $this->request = $request;
    }

    /**
     * @return string
     */
    public function rootDir()
    {
        return $this->rootDir;
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