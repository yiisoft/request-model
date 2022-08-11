<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Attribute;

use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function testInstance(): void
    {
        $instance = new Request('foo');

        $this->assertEquals(HandlerParameterAttributeInterface::REQUEST_ATTRIBUTE, $instance->getType());
        $this->assertEquals('foo', $instance->getName());
    }
}
