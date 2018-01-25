<?php

class RequestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Nono\Request
     */
    protected $request;

    public function setUp()
    {
        $_SERVER['REQUEST_URI'] = '/profile/123?show=settings';
        $_SERVER['QUERY_STRING'] = 'show=settings';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_SCHEME'] = 'http';
        $_SERVER['HTTP_HOST'] = 'dummy.dev';
        $_REQUEST['show'] = $_GET['show'] = $_POST['show'] = 'settings';
        $this->request = new Nono\Request();
    }

    public function testUri()
    {
        self::assertEquals('/profile/123', $this->request->uri());
    }

    public function testIsHttps()
    {
        self::assertEquals(false, $this->request->isHttps());
    }

    public function testHost()
    {
        self::assertEquals('dummy.dev', $this->request->host());
    }

    public function testMethod()
    {
        self::assertEquals('GET', $this->request->method());
    }

    public function testRequestTimeFloat()
    {
        self::assertTrue(is_numeric($this->request->requestTimeFloat()));
        self::assertEquals($_SERVER['REQUEST_TIME_FLOAT'], $this->request->requestTimeFloat());
    }

    public function testElapsedRequestTimeFloat()
    {
        self::assertTrue(is_numeric($this->request->elapsedRequestTimeFloat()));
    }

    public function testRedirect()
    {
        self::expectOutputString("<script>location.replace('/logout');</script>");
        $this->request->redirect('/logout');
    }

    public function testGet()
    {
        self::assertEquals('settings', $this->request->get('show'));
    }

    public function testPost()
    {
        self::assertEquals('settings', $this->request->post('show'));
    }

    public function testRequest()
    {
        self::assertEquals('settings', $this->request->request('show'));
    }

    public function testServer()
    {
        self::assertNotEmpty($this->request->server('REQUEST_TIME'));
    }

    public function testServerWithDefaultValue()
    {
        self::assertEquals('default', $this->request->server('NOTHING_HERE', 'default'));
    }

    public function testServerWithoutArgument()
    {
        self::assertTrue(is_array($this->request->server()));
    }
}
