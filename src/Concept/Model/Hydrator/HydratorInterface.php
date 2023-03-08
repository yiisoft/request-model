<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Concept\Model\Hydrator;

interface HydratorInterface
{
    /**
     * @psalm-param array<string,mixed> $data
     */
    public function hydrate(object $object, array $data): void;
}
