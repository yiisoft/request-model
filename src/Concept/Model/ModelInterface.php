<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Concept\Model;

interface ModelInterface
{
    /**
     * @psalm-param array<string,mixed> $data
     */
    public function setRawData(array $data): void;
}
