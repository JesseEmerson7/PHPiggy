<?php

declare(strict_types=1);

namespace Framework;

use ReflectionClass, ReflectionNamedType;
use Framework\Exceptions\ContainerExceptions;

class Container
{
    private array $definitions = [];

    public function addDEfinitions(array $newDefinitions)
    {
        $this->definitions = [...$this->definitions, ...$newDefinitions];
    }

    public function resolve(string $className)
    {
        $reflectionClass = new ReflectionClass($className);
        if (!$reflectionClass->isInstantiable()) {
            throw new ContainerExceptions("Class {$className} is non instantiable");
        }

        $constructor = $reflectionClass->getConstructor();

        if (!$constructor) {
            return $className;
        }
        $params = $constructor->getParameters();
        if (count($params) === 0) {
            return new $className;
        }

        $dependencies = [];
        foreach ($params as $param) {
            $name = $param->getName();
            $type = $param->getType();
            if (!$type) {
                throw new ContainerExceptions("Failed to resolve class {$className} because param {$name} is missing a type hint.");
            }
            if (!$type instanceof ReflectionNamedType || $type->isBuiltin()) {
                throw new ContainerExceptions("Failed to resolve class {$className} becuase invalid param name.");
            }
            $dependencies[] = $this->get($type->getName());
        }

        return $reflectionClass->newInstanceArgs($dependencies);
    }
    public function get(string $id)
    {
        if (!array_key_exists($id, $this->definitions)) {
            throw new ContainerExceptions("class {$id} does not existin container");
        }

        $factory = $this->definitions[$id];
        $depencency = $factory();

        return $depencency;
    }
}
