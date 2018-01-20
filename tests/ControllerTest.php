<?php

class ControllerTest extends PHPUnit_Framework_TestCase
{
    public function testConcreteMethod()
    {
        $stub = $this->getMockForAbstractClass('Nono\Controller', [new \Nono\Container()]);
        self::assertInstanceOf('Nono\Controller', $stub);
    }
}
