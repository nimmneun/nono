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

    public function testSimpleExtendedControllerView(): void
    {
        $class = new class extends Controller {
            public function render(string $template, array $data = []): string
            {
                return $this->view->render($template, $data);
            }
        };

        self::assertSame('<p>hello Bob</p>', $class->render('<p>hello Bob</p>'));
    }
}
