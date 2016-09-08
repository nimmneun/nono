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
        $response = $this->router->route('GET', '/profile/Harry');
        $this->assertNotEmpty($response);
        $this->assertCount(2, array_filter($response));

        $response = $this->router->route('GET', '/products/ABC123/weight/2.5');
        $this->assertEquals('ABC123', $response[1][1]);
        $this->assertEquals('2.5', $response[1][2]);

        $response = $this->router->route('GET', '/');
        $this->assertEquals('Nono\Request::requestTimeFloat', $response[0]);
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
