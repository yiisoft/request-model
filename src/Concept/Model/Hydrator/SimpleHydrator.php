<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Concept\Model\Hydrator;

use Closure;
use ReflectionClass;
use ReflectionProperty;

/**
 * @todo Учесть readonly-свойства
 */
final class SimpleHydrator implements HydratorInterface
{
    /**
     * @psalm-var array<class-string,array<string,ReflectionProperty>
     */
    private static array $cache = [];

    public function hydrate(object $object, array $data): void
    {
        foreach ($data as $key => $value) {
            $this->setPropertyValue($object, $key, $value);
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
