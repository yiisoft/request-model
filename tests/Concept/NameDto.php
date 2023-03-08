<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Tests\Concept;

final class NameDto
{
    public function __construct(
        private string $first,
        private string $last,
    ) {
    }

    public function getFirst(): string
    {
        return $this->first;
    }

    public function getLast(): string
    {
        return $this->last;
    }
}
