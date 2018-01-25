<?php

namespace Nono\Tests;

use Nono\Container;

class ControllerTest extends \PHPUnit_Framework_TestCase
{
    public function testConcreteMethod()
    {
        $stub = $this->getMockForAbstractClass('Nono\Controller', [new Container()]);
        self::assertInstanceOf('Nono\Controller', $stub);
    }
}
