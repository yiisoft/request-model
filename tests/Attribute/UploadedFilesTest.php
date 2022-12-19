<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Attribute;

use PHPUnit\Framework\TestCase;

class UploadedFilesTest extends TestCase
{
    public function testInstance(): void
    {
        $instance = new UploadedFiles();
    }
}
