<?php

namespace nimmneun\Nono\Tests;

use nimmneun\Nono\Request;
use nimmneun\Nono\Router;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    protected Router $router;

    public function setUp(): void
    {
        $this->router = new Router();

        $this->router->add('GET', '/profile/{name}', fn($request, $name) => null);
        $this->router->add('GET', '/products/{sku}/weight/{weight}', fn($request, $sku, $weight) => null);
        $this->router->add('GET', '/', [Request::class, 'requestTimeFloat']);
        $this->router->add('GET', '/old', 'Nono\Request::requestTimeFloat');
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
        self::assertEquals([Request::class, 'requestTimeFloat'], $result[0]);

        $result = $this->router->route('GET', '/old');
        self::assertEquals('Nono\Request::requestTimeFloat', $result[0]);
    }

    public function testInvalidRoute()
    {
        $this->expectExceptionMessage("Route /some/invalid/route not found");
        $this->expectException(\Exception::class);
        $this->router->route('GET', '/some/invalid/route');
    }
}
