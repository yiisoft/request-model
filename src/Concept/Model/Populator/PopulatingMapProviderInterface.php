<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Concept\Model\Populator;

interface PopulatingMapProviderInterface
{
    /**
     * @psalm-return array<string,string>
     */
    public function getPopulatingMap(): array;
}
