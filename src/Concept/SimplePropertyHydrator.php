<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Concept;

use Closure;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionProperty;
use ReflectionUnionType;

/**
 * @todo Учесть readonly-свойства
 */
final class SimplePropertyHydrator implements PropertyHydratorInterface
{
    /**
     * @psalm-var array<class-string,array<string,ReflectionProperty>
     */
    private static array $cache = [];

    public function hydrate(object $object, string $propertyName, mixed $value): void
    {
        $property = $this->getProperty($object, $propertyName);
        if ($property === null) {
            return;
        }

        $type = $property->getType();

        if ($type === null) {
            $this->setPropertyValue($object, $propertyName, $value);
            return;
        }

        if ($type instanceof ReflectionNamedType) {
            $this->setPropertyValue($object, $propertyName, (string) $value);
            return;
        }

        if ($type instanceof ReflectionUnionType) {
            $this->setPropertyValue($object, $propertyName, (string) $value);
            return;
        }
    }

    private function setPropertyValue(object $object, string $propertyName, mixed $value): void
    {
        $setter = static function (object $object, string $propertyName, mixed $value): void {
            $object->$propertyName = $value;
        };
        $setter = Closure::bind($setter, null, $object);
        $setter($object, $propertyName, $value);
    }

    private function getProperty(object $object, string $propertyName): ?ReflectionProperty
    {
        if (!isset(self::$cache[$object::class])) {
            $reflection = new ReflectionClass($object);
            foreach ($reflection->getProperties() as $property) {
                if ($property->isStatic()) {
                    continue;
                }

                self::$cache[$object::class][$property->getName()] = $property;
            }
        }

        return self::$cache[$object::class][$propertyName] ?? null;
    }
}
