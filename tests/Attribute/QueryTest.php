<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Attribute;

use PHPUnit\Framework\TestCase;

class QueryTest extends TestCase
{
    public function testInstance(): void
    {
        $instance = new Query('page');

        $this->assertEquals('page', $instance->getName());
    }
}
