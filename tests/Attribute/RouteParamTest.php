<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Attribute;

use PHPUnit\Framework\TestCase;

class RouteParamTest extends TestCase
{
    public function testInstance(): void
    {
        $instance = new RouteParam('id');

        $this->assertEquals(HandlerParameterAttributeInterface::ROUTE_PARAM, $instance->getType());
        $this->assertEquals('id', $instance->getName());
    }
}
