<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Tests\Concept;

final class AbcModel
{
    public function __construct(
        private string $a = '.',
        private string $b = '.',
        private string $c = '.',
    ) {
    }

    public function getA(): string
    {
        return $this->a;
    }

    public function getB(): string
    {
        return $this->b;
    }

    public function getC(): string
    {
        return $this->c;
    }
}
