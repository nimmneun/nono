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

        $this->router->add('GET', '/profile/{name}', function ($request, $name) {
        });
        $this->router->any(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'],
            '/products/{sku}/weight/{weight}', function ($request, $sku, $weight) {
            });
        $this->router->add('GET', '/', 'Nono\Request::requestTimeFloat');
        $this->router->add('GET', '/nope', 'NoValidClass::index');
    }

    public function testExistingRoutes()
    {
        $result = $this->router->route('GET', '/profile/Harry');
        self::assertNotEmpty($result);
        self::assertCount(2, array_filter($result));

        $result = $this->router->route('GET', '/products/ABC123/weight/2.5');
        self::assertEquals('ABC123', $result[1][1]);
        self::assertEquals('2.5', $result[1][2]);

        $result = $this->router->route('GET', '/');
        self::assertEquals('Nono\Request::requestTimeFloat', $result[0]);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Route /some/invalid/route not found
     */
    public function testInvalidRoute()
    {
        $this->router->route('GET', '/some/invalid/route');
    }
}
