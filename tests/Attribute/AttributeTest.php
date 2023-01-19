<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Attribute;

use PHPUnit\Framework\TestCase;

abstract class AttributeTest extends TestCase
{
    public function testResolve(): void
    {
        $instance = new Query('page');

        $this->assertEquals('page', $instance->getName());
        $this->assertEquals(QueryResolver::class, $instance->getResolverClassName());
    }
}
