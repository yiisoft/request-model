<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Attribute;

use PHPUnit\Framework\TestCase;

class ParsedBodyTest extends TestCase
{
    public function testInstance(): void
    {
        $instance = new ParsedBody();

        $this->assertEquals(HandlerParameterAttributeInterface::REQUEST_BODY, $instance->getType());
        $this->assertNull($instance->getName());
    }
}
