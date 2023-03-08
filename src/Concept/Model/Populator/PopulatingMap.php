<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Concept\Model\Populator;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class PopulatingMap
{
    /**
     * @psalm-param array<string,string> $map
     */
    public function __construct(
        private array $map,
    ) {
    }

    /**
     * @psalm-return array<string,string>
     */
    public function getMap(): array
    {
        return $this->map;
    }
}
