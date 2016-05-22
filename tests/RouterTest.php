<?php

class RouterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Nono\Router
     */
    protected $router;

    public function setUp()
    {
        $this->router = new \Nono\Router();
        $this->router->get('/profile/{name}', function($request, $name) {
            return 'Hello ' . $name;
        });
    }

    public function testExistingRoute()
    {
        $_SERVER['REQUEST_URI'] = '/profile/Harry';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $response = $this->router->route(new \Nono\Request());
        $this->assertEquals('Hello Harry', $response);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Route /some/invalid/route not found!
     */
    public function testInvalidRoute()
    {
        $_SERVER['REQUEST_URI'] = '/some/invalid/route';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->router->route(new \Nono\Request());
    }
}
