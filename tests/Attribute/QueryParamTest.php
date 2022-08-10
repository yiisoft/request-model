<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Attribute;

use PHPUnit\Framework\TestCase;

class QueryParamTest extends TestCase
{
    public function testInstance(): void
    {
        $instance = new QueryParam('page');

        $this->assertEquals(HandlerParameterAttributeInterface::QUERY_PARAM, $instance->getType());
        $this->assertEquals('page', $instance->getName());
    }
}
