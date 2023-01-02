<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Attribute;

use PHPUnit\Framework\TestCase;

class RouteTest extends TestCase
{
    public function testInstance(): void
    {
        $instance = new Route('id');

        $this->assertEquals('id', $instance->getName());
        $this->assertEquals(RouteResolver::class, $instance->getResolverClassName());
    }
}
