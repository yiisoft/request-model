<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Concept;

interface PropertyHydratorInterface
{
    public function hydrate(object $object, string $propertyName, mixed $value): void;
}
