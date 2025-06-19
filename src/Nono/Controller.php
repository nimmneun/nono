<?php

declare(strict_types=1);

namespace nimmneun\Nono;

abstract class Controller
{
    public function __construct(protected ?Container $container = null)
    {
        $this->container = $container ?? new Container();
    }
}