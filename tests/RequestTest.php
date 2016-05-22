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
        $this->assertEquals('/profile/123', $this->request->uri());
    }

    public function testUrl()
    {
        $this->assertEquals('http://dummy.dev/profile/123', $this->request->url());
    }

    public function testHost()
    {
        $this->assertEquals('dummy.dev', $this->request->host());
    }

    public function testRequestTimeFloat()
    {
        $this->assertTrue(is_numeric($this->request->requestTimeFloat()));
        $this->assertEquals($_SERVER['REQUEST_TIME_FLOAT'], $this->request->requestTimeFloat());
    }

    public function testGet()
    {
        $this->assertEquals('settings', $this->request->get('show'));
    }

    public function testPost()
    {
        $this->assertEquals('settings', $this->request->post('show'));
    }

    public function testRequest()
    {
        $this->assertEquals('settings', $this->request->request('show'));
    }

    public function testServer()
    {
        $this->assertNotEmpty($this->request->server('REQUEST_TIME'));
    }
}
