<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Concept\Model\Populator;

use ReflectionClass;
use ReflectionNamedType;
use ReflectionProperty;
use ReflectionType;
use ReflectionUnionType;
use Yiisoft\Arrays\ArrayHelper;
use Yiisoft\Injector\Injector;
use Yiisoft\RequestModel\Concept\Model\Hydrator\HydratorInterface;

final class Populator
{
    public function __construct(
        private HydratorInterface $hydrator,
        private Injector $injector,
    ) {
    }

    /**
     * @psalm-param array<string,mixed> $data
     * @psalm-param array<string,string>|PopulatingMapProviderInterface $map
     */
    public function populate(object $object, array $data, array|PopulatingMapProviderInterface $map = []): void
    {
        $this->hydrator->hydrate($object, $this->getHydrateData($object, $data, $map));
    }

    /**
     * @psalm-template T
     *
     * @psalm-param class-string<T> $class
     *
     * @psalm-return T
     */
    public function createObject(
        string $class,
        array $data,
        array|PopulatingMapProviderInterface|null $map = null
    ): object {
        if ($map instanceof PopulatingMapProviderInterface) {
            $map = $map->getPopulatingMap();
        }

        $constructorArguments = $this->getConstructorArguments($class, $data, $map);

        return $this->injector->make($class, $constructorArguments);
    }

    private function getConstructorArguments(string $class, array $data, ?array $map): array
    {
        $constructor = (new ReflectionClass($class))->getConstructor();
        if ($constructor === null) {
            return [];
        }

        $result = [];
        foreach ($constructor->getParameters() as $parameter) {
            if (!$parameter->isPromoted()) {
                continue;
            }
            $parameterName = $parameter->getName();
            try {
                $result[$parameterName] = $this->resolve($parameterName, $parameter->getType(), $data, $map);
            } catch (NoResolveException) {
            }
        }

        return $result;
    }

    /**
     * @psalm-param array<string,list<string>> $map
     *
     * @throws NoResolveException
     */
    private function resolve(
        string $name,
        ?ReflectionType $type,
        array $data,
        ?array $map,
        ?object $currentObject = null
    ): mixed {
        $dataKey = $map[$name] ?? $name;

        if (ArrayHelper::keyExists($data, $dataKey)) {
            $value = ArrayHelper::getValue($data, $dataKey);
            if (!is_array($value)) {
                return $value;
            }

            $values = $value;
            $nameWithDot = $name . '.';
            $nameWithDotLength = strlen($name . '.');
            foreach ($data as $dk => $dv) {
                if (str_starts_with($dk, $nameWithDot)) {
                    $values[substr($dk, $nameWithDotLength)] = $dv;
                }
            }

            if ($currentObject === null) {
                $class = $this->getClassFromType($type);
                if ($class === null) {
                    throw new NoResolveException();
                }
                $currentObject = $this->createObject($class, $values);
            }

            $data = $this->getHydrateData($currentObject, $values, []);
            $this->hydrator->hydrate($currentObject, $data);

            return $currentObject;
        }

//        if ($this->isCanBeObject($type)) {
//            $propertyValue = $property->getValue($object);
//            if (is_object($propertyValue)) {
//                $this->getHydrateData($propertyValue, $data, [])
//                    }
//            $hydrateData[$propertyName] = $t;
//        }

        throw new NoResolveException();
    }

    /**
     * @psalm-param array<string,string|list<string>> $map
     */
    private function getHydrateData(object $object, array $data, ?array $map): array
    {
//        $properties = $this->getProperties($object);
//        $propertyNames = array_keys($properties);
//
//        $hydrateData = [];
//        foreach ($data as $dataKeyPath => $value) {
//            $dataKeyPath = (string) $dataKeyPath;
//
//            $propertyKey = explode('.', $map[$dataKeyPath] ?? $dataKeyPath);
//
//        }

        $map = array_map(
            static fn (string|array $item) => is_array($item) ? $item : explode('.', $item),
            $map
        );

        $hydrateData = [];

        foreach ($this->getProperties($object) as $property) {
            $propertyName = $property->getName();
            try {
                $hydrateData[$propertyName] = $this->resolve($propertyName, $property->getType(), $data, $map, $object);
            } catch (NoResolveException) {
            }
        }

        return $hydrateData;
    }

    /**
     * @psalm-return array<string,array>
     */
    private function prepareNestedData(array $raw): array
    {
        $data = [];

        foreach ($raw as $key => $value) {
            if (is_int($key)) {
                continue;
            }

            $item = explode('.', $key, 2);
            if (count($item) === 1) {
                if (is_array($value)) {
                    $data[$key] = $value;
                }
                continue;
            }

            $data[$item[0]][$item[1]] = $value;
        }

        return $data;
    }

    /**
     * @psalm-return array<string, ReflectionProperty>
     */
    private function getProperties(object $object): array
    {
        $result = [];

        $properties = (new ReflectionClass($object))->getProperties();
        foreach ($properties as $property) {
            if ($property->isStatic() || $property->isReadOnly()) {
                continue;
            }

            $result[$property->getName()] = $property;
        }

        return $result;
    }

    private function isCanBeObject(ReflectionProperty $property): bool
    {
    }

    private function isCanBeArray(ReflectionProperty $property): bool
    {
    }

    private function getClassFromType(?ReflectionType $type): ?string
    {
        if ($type instanceof ReflectionNamedType) {
            $types = [$type];
        } elseif ($type instanceof ReflectionUnionType) {
            $types = $type->getTypes();
        } else {
            $types = [];
        }

        foreach ($types as $t) {
            if ($t->isBuiltin()) {
                continue;
            }

            $class = $t->getName();
            $reflection = new ReflectionClass($class);
            if ($reflection->isInstantiable()) {
                return $class;
            }
        }

        return null;
    }
}
