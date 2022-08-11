<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Attribute;

use PHPUnit\Framework\TestCase;

class UploadedFilesTest extends TestCase
{
    public function testInstance(): void
    {
        $instance = new UploadedFiles();

        $this->assertEquals(HandlerParameterAttributeInterface::UPLOADED_FILES, $instance->getType());
        $this->assertNull($instance->getName());
    }
}
