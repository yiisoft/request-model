<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Attribute;

use PHPUnit\Framework\TestCase;
use Yiisoft\RequestModel\YiiRouter\Attribute\Route;
use Yiisoft\RequestModel\YiiRouter\Attribute\RouteResolver;

class RouteTest extends TestCase
{
    public function testInstance(): void
    {
        $instance = new Route('id');

        $this->assertEquals('id', $instance->getName());
        $this->assertEquals(RouteResolver::class, $instance->getResolverClassName());
    }
}
