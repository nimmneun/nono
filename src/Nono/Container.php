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

    public function bind(string $id, mixed $resolver): void
    {
        $this[$id] = $resolver;
    }

    /**
     * @throws ReflectionException
     */
    public function make(string $id): mixed
    {
        if (!parent::offsetExists($id)) {
            return class_exists($id)
                ? $this->autowire($id)
                : null;
        }

        $entry = parent::offsetGet($id);

        return is_callable($entry)
            ? $entry($this)
            : $entry;
    }

    /**
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
