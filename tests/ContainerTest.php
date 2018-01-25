<?php

namespace Nono\Tests;

use Nono\Container;

class ContainerTest extends \PHPUnit_Framework_TestCase
{
    public function testGet()
    {
        $container = new Container(['dummy' => 'dummy']);
        self::assertEquals('dummy', $container->get('dummy'));
    }

    public function testHas()
    {
        $container = new Container(['dummy' => 'dummy']);
        self::assertTrue(true, $container->has('dummy'));
    }
}
