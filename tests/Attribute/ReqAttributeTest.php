<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Attribute;

use PHPUnit\Framework\TestCase;

class ReqAttributeTest extends TestCase
{
    public function testInstance(): void
    {
        $instance = new ReqAttribute('foo');

        $this->assertEquals(HandlerParameterAttributeInterface::REQUEST_ATTRIBUTE, $instance->getType());
        $this->assertEquals('foo', $instance->getName());
    }
}
