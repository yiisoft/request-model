<?php
declare(strict_types=1);

namespace Yiisoft\RequestModel\Concept\Model;

interface ModelHydratorInterface
{
    /**
     * @psalm-param array<string,mixed> $data
     */
    public function hydrate(ModelInterface $object, array $data): void;
}
