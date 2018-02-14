<?php

namespace Nono\Tests;

use Nono\Application;
use Nono\Container;
use Nono\Request;
use Nono\Router;

class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    public function testGet()
    {
        $router = $this->createMock(Router::class);
        $router
            ->expects(self::once())
            ->method('add')
            ->with(self::equalTo('GET'), self::equalTo('/'), self::equalTo('PartyController::party'));
        $app = new Application($router, $this->createMock(Request::class));

        $app->get('/', 'PartyController::party');
    }

    public function testPost()
    {
        $router = $this->createMock(Router::class);
        $router
            ->expects(self::once())
            ->method('add')
            ->with(self::equalTo('POST'), self::equalTo('/'), self::isInstanceOf(\Closure::class));
        $app = new Application($router, $this->createMock(Request::class));

        $app->post('/', function () {
        });
    }

    public function testPut()
    {
        $router = $this->createMock(Router::class);
        $router
            ->expects(self::once())
            ->method('add')
            ->with(self::equalTo('PUT'), self::equalTo('/user/{id}'), self::isInstanceOf(\Closure::class));
        $app = new Application($router, $this->createMock(Request::class));

        $app->put('/user/{id}', function () {
        });
    }

    public function testDelete()
    {
        $router = $this->createMock(Router::class);
        $router
            ->expects(self::once())
            ->method('add')
            ->with(self::equalTo('DELETE'), self::equalTo('/user/{id}'), self::isInstanceOf(\Closure::class));
        $app = new Application($router, $this->createMock(Request::class));

        $app->delete('/user/{id}', function () {
        });
    }

    public function testAny()
    {
        $router = $this->createMock(Router::class);
        $router
            ->expects(self::once())
            ->method('any')
            ->with(self::equalTo(['DELETE', 'POST', 'PUT']), self::equalTo('/user/{id}'),
                self::isInstanceOf(\Closure::class));
        $app = new Application($router, $this->createMock(Request::class));

        $app->any(['DELETE', 'POST', 'PUT'], '/user/{id}', function () {
        });
    }

    public function testContainer()
    {
        self::assertInstanceOf(Container::class, (new Application())->container());
    }

    public function testRespond()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/hello';

        $app = new Application();
        $app->get('/hello', function (Request $request) {
            echo 'hello';
        });
        self::expectOutputString('hello');
        $app->respond();
    }

    public function testRun()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/profile/123';

        $app = new Application();
        $app->get('/profile/{id}', function (Request $request, $id) {
            echo $request->uriWithQuery() . ' has id ' . $id;
        });
        self::assertEquals('/profile/123 has id 123', $app->run());
    }

    public function testRunWithInvalidClass()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/dummy';

        $app = new Application();
        $app->get('/dummy', 'InvalidController::lol');
        self::assertEquals('Failed to call InvalidController::lol', $app->run());
    }

    public function testRunWithInvalidCallable()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/dummy';

        $app = new Application();
        $app->get('/dummy', 'somethingIsMissing');
        self::assertEquals('Failed to call callable', $app->run());
    }
}
