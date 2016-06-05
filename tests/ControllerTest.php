<?php

class ControllerTest extends PHPUnit_Framework_TestCase
{
    public function testConcreteMethod()
    {
        $stub = $this->getMockForAbstractClass('Nono\Controller', [new \Nono\Request()]);
        $this->assertInstanceOf('Nono\Controller', $stub);
    }
}
