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

        $this->router->any('/products/{sku}/weight/{weight}', function($request, $sku, $weight) {
            return $sku . '\'s weight set to '. $weight .' lbs';
        });

        $this->router->get('/', 'Nono\Request::requestTimeFloat');
        $this->router->get('/nope', 'NoValidClass::index');
    }

    public function testExistingRoutes()
    {
        $_SERVER['REQUEST_URI'] = '/profile/Harry';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $response = $this->router->route(new \Nono\Request());
        $this->assertEquals('Hello Harry', $response);

        $_SERVER['REQUEST_URI'] = '/products/ABC123/weight/2.5';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $response = $this->router->route(new \Nono\Request());
        $this->assertEquals('ABC123\'s weight set to 2.5 lbs', $response);

        $_SERVER['REQUEST_URI'] = '/';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $response = $this->router->route(new \Nono\Request());
        $this->assertEquals($_SERVER['REQUEST_TIME_FLOAT'], $response);
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

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Failed to call callable
     */
    public function testInvalidClass()
    {
        $_SERVER['REQUEST_URI'] = '/nope';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->router->route(new \Nono\Request());
    }
}
