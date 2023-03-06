<?php
declare(strict_types=1);

namespace Yiisoft\RequestModel\Concept\RequestModel\Attribute;

interface RequestAttributeInterface
{
    public function getResolverClassName(): string;
}
