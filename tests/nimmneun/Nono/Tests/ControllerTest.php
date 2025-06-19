<?php

namespace nimmneun\Nono\Tests;

use nimmneun\Nono\Container;
use nimmneun\Nono\Controller;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

class ControllerTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testConcreteMethod()
    {
        $stub = $this->getMockBuilder(Controller::class)
            ->setConstructorArgs([new Container()])
            ->getMock();
        self::assertInstanceOf('nimmneun\Nono\Controller', $stub);
    }
}
