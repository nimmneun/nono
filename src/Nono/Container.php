<?php

declare(strict_types=1);

namespace nimmneun\Nono;

use ArrayObject;
use ReflectionClass;
use ReflectionException;
use ReflectionParameter;
use RuntimeException;

class Container extends ArrayObject
{
    public function bind(string $key, mixed $resolver): void
    {
        $this[$key] = $resolver;
    }

    /**
     * @throws ReflectionException
     */
    public function make(string $key): mixed
    {
        if (!$this->offsetExists($key)) {
            return class_exists($key)
                ? $this->autowire($key)
                : null;
        }

        $entry = $this->offsetGet($key);

        return is_callable($entry)
            ? $entry($this)
            : $entry;
    }

    /**
     * Auto-wire a class by resolving its constructor dependencies.
     * @throws ReflectionException
     */
    private function autowire(string $class): object
    {
        $ref = new ReflectionClass($class);
        $ctor = $ref->getConstructor();

        if ($ctor?->getNumberOfParameters() < 1) {
            return $ref->newInstance();
        }

        $args = [];
        foreach ($ctor->getParameters() as $param) {
            $args[] = $this->resolveParameter($param);
        }

        return $ref->newInstanceArgs($args);
    }

    /**
     * @throws RuntimeException
     * @throws ReflectionException
     */
    private function resolveParameter(ReflectionParameter $param): mixed
    {
        $type = $param->getType();
        if ($type && !$type->isBuiltin()) {
            return $this->make($type->getName());
        }

        if ($param->isDefaultValueAvailable()) {
            return $param->getDefaultValue();
        }

        throw new RuntimeException(
            sprintf(
                'Cannot resolve parameter $%s for %s',
                $param->getName(),
                $param->getDeclaringClass()->getName(),
            ),
        );
    }
}