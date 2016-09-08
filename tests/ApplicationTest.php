<?php

class ApplicationTest extends PHPUnit_Framework_TestCase
{
    public function testGet()
    {
        $router = $this->getMockBuilder(\Nono\Router::class)->getMock();
        $router
            ->expects($this->once())
            ->method('add')
            ->with($this->equalTo('GET'), $this->equalTo('/'), $this->equalTo('PartyController::party'));
        $app = new \Nono\Application($router, $this->getMockBuilder(\Nono\Request::class)->getMock());

        $app->get('/', 'PartyController::party');
    }

    public function testPost()
    {
        $router = $this->getMockBuilder(\Nono\Router::class)->getMock();
        $router
            ->expects($this->once())
            ->method('add')
            ->with($this->equalTo('POST'), $this->equalTo('/'), $this->isInstanceOf(Closure::class));
        $app = new \Nono\Application($router, $this->getMockBuilder(\Nono\Request::class)->getMock());

        $app->post('/', function () {
        });
    }

    public function testPut()
    {
        $router = $this->createMock(\Nono\Router::class);
        $router
            ->expects($this->once())
            ->method('add')
            ->with($this->equalTo('PUT'), $this->equalTo('/user/{id}'), $this->isInstanceOf(Closure::class));
        $app = new \Nono\Application($router, $this->getMockBuilder(\Nono\Request::class)->getMock());

        $app->put('/user/{id}', function () {
        });
    }

    public function testDelete()
    {
        $router = $this->getMockBuilder(\Nono\Router::class)->getMock();
        $router
            ->expects($this->once())
            ->method('add')
            ->with($this->equalTo('DELETE'), $this->equalTo('/user/{id}'),
                $this->isInstanceOf(Closure::class));
        $app = new \Nono\Application($router, $this->getMockBuilder(\Nono\Request::class)->getMock());

        $app->delete('/user/{id}', function () {
        });
    }

    public function testAny()
    {
        $router = $this->getMockBuilder(\Nono\Router::class)->getMock();
        $router
            ->expects($this->once())
            ->method('any')
            ->with($this->equalTo(['DELETE', 'POST', 'PUT']), $this->equalTo('/user/{id}'),
                $this->isInstanceOf(Closure::class));
        $app = new \Nono\Application($router, $this->createMock(\Nono\Request::class));

        $app->any(['DELETE', 'POST', 'PUT'], '/user/{id}', function () {
        });
    }

    public function testRun()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/profile/123';

        $app = new \Nono\Application();
        $app->get('/profile/{id}', function (\Nono\Request $request, $id) {
            echo $request->plainUri() . ' has id ' . $id;
        });
        $this->assertEquals('/profile/123 has id 123', $app->run());
    }
}
