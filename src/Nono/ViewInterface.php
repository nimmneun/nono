<?php

namespace nimmneun\Nono;

interface ViewInterface
{
    public function render(string $template, array $data = []): string;
}