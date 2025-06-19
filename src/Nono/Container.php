<?php

declare(strict_types=1);

namespace nimmneun\Nono;

use ArrayObject;
use ReflectionClass;
use ReflectionException;
use ReflectionParameter;

class Container extends ArrayObject
{
    /**
     * @throws ReflectionException
     */
    public function offsetGet(mixed $key): mixed
    {
        return $this->make($key);
    }

    public function bind(string $key, mixed $resolver): void
    {
        $this[$key] = $resolver;
    }

    /**
     * @throws ReflectionException
     */
    public function make(string $key): mixed
    {
        if (!parent::offsetExists($key)) {
            return class_exists($key)
                ? $this->autowire($key)
                : null;
        }

        $entry = parent::offsetGet($key);

        return is_callable($entry)
            ? $entry($this)
            : $entry;
    }

    /**
     * @throws ReflectionException
     */
    protected function autowire(string $class): object
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
     * @throws ReflectionException
     */
    protected function resolveParameter(ReflectionParameter $param): mixed
    {
        $type = $param->getType();
        if ($type && method_exists($type, 'isBuiltin') && !$type->isBuiltin()) {
            return $this->make($type->getName());
        }

        if ($param->isDefaultValueAvailable()) {
            return $param->getDefaultValue();
        }

        // bad idea? not sure yet.
        if ($this->offsetExists($param->getName())) {
            $tmp = $this->offsetGet($param->getName());
            return is_callable($tmp) ? $tmp($this) : $tmp;
        }

        if ($param->allowsNull()) {
            return null;
        }

        throw new ReflectionException(
            sprintf(
                'Cannot resolve parameter $%s for %s',
                $param->getName(),
                $param->getDeclaringClass()->getName(),
            ),
        );
    }
}
