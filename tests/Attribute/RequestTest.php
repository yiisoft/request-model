<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Attribute;

use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function testInstance(): void
    {
        $instance = new Request('foo');

        $this->assertEquals('foo', $instance->getName());
        $this->assertEquals(RequestResolver::class, $instance->getResolverClassName());
    }
}
