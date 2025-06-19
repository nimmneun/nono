<?php

declare(strict_types=1);

namespace nimmneun\Nono;

abstract class Controller
{
    public function __construct(
        protected ?Container $container = null,
        protected ?ViewInterface $view = null,
    ) {
        $this->container = $container ?? new Container();
        $this->view = $view ?? new class implements ViewInterface {
            public function render(string $template, array $data = []): string
            {
                extract($data);
                return $template;
            }
        };
    }
}