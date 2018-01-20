<?php

class ApplicationTest extends PHPUnit_Framework_TestCase
{
    public function testGet()
    {
        $router = $this->getMock(\Nono\Router::class);
        $router
            ->expects(self::once())
            ->method('add')
            ->with($this->equalTo('GET'), $this->equalTo('/'), $this->equalTo('PartyController::party'));
        $app = new \Nono\Application($router, $this->getMock(\Nono\Request::class));

        $app->get('/', 'PartyController::party');
    }

    public function testPost()
    {
        $router = $this->getMock(\Nono\Router::class);
        $router
            ->expects(self::once())
            ->method('add')
            ->with($this->equalTo('POST'), $this->equalTo('/'), $this->isInstanceOf(Closure::class));
        $app = new \Nono\Application($router, $this->getMock(\Nono\Request::class));

        $app->post('/', function () {
        });
    }

    public function testPut()
    {
        $router = $this->getMock(\Nono\Router::class);
        $router
            ->expects(self::once())
            ->method('add')
            ->with($this->equalTo('PUT'), $this->equalTo('/user/{id}'), $this->isInstanceOf(Closure::class));
        $app = new \Nono\Application($router, $this->getMock(\Nono\Request::class));

        $app->put('/user/{id}', function () {
        });
    }

    public function testDelete()
    {
        $router = $this->getMock(\Nono\Router::class);
        $router
            ->expects(self::once())
            ->method('add')
            ->with($this->equalTo('DELETE'), $this->equalTo('/user/{id}'),
                $this->isInstanceOf(Closure::class));
        $app = new \Nono\Application($router, $this->getMock(\Nono\Request::class));

        $app->delete('/user/{id}', function () {
        });
    }

    public function testAny()
    {
        $router = $this->getMock(\Nono\Router::class);
        $router
            ->expects(self::once())
            ->method('any')
            ->with($this->equalTo(['DELETE', 'POST', 'PUT']), $this->equalTo('/user/{id}'),
                $this->isInstanceOf(Closure::class));
        $app = new \Nono\Application($router, $this->getMock(\Nono\Request::class));

        $app->any(['DELETE', 'POST', 'PUT'], '/user/{id}', function () {
        });
    }

    public function testContainer()
    {
        self::assertInstanceOf(\Nono\Container::class, (new \Nono\Application())->container());
    }

    public function testRun()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/profile/123';

        $app = new \Nono\Application();
        $app->get('/profile/{id}', function (\Nono\Request $request, $id) {
            echo $request->plainUri() . ' has id ' . $id;
        });
        self::assertEquals('/profile/123 has id 123', $app->run());
    }

    public function testRunWithInvalidClass()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/dummy';

        $app = new \Nono\Application();
        $app->get('/dummy', 'InvalidController::lol');
        self::assertEquals('Failed to call InvalidController::lol', $app->run());
    }

    public function testRunWithInvalidCallable()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/dummy';

        $app = new \Nono\Application();
        $app->get('/dummy', 'somethingIsMissing');
        self::assertEquals('Failed to call callable', $app->run());
    }
}
