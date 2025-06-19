<?php

namespace nimmneun\Nono\Tests;

use nimmneun\Nono\Container;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    public function testOffsetGet()
    {
        $container = new Container(['dummy' => 'dummy']);
        self::assertEquals('dummy', $container->offsetGet('dummy'));
    }

    public function testOffsetExists()
    {
        $container = new Container(['dummy' => 'dummy']);
        self::assertTrue($container->offsetExists('dummy'));
    }
}
