<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Tests\Support;

use Yiisoft\RequestModel\Attribute\HandlerParameterAttributeInterface;

#[\Attribute(flags: \Attribute::TARGET_PARAMETER)]
class MockAttribute implements HandlerParameterAttributeInterface
{
    public function getResolverClassName(): string
    {
        return MockHandler::class;
    }
}
