<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Concept;

interface ModelInterface
{
    /**
     * @psalm-param array<string,mixed> $data
     */
    public function setRawData(array $data): void;

    /**
     * @psalm-return array<string,mixed>
     */
    public function getRawData(): array;
}
