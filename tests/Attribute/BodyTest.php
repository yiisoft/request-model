<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Attribute;

use PHPUnit\Framework\TestCase;

class BodyTest extends TestCase
{
    public function testInstance(): void
    {
        $instance = new Body();

        $this->assertNull($instance->getName());
    }
}
