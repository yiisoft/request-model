<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Tests;

use Yiisoft\RequestModel\RequestDataSet;
use Yiisoft\RequestModel\Tests\Support\TestCase;

class RequestDataSetTest extends TestCase
{
    public function testGetAttributeReturnCorrectValue(): void
    {
        $this->assertSame('value', $this->createRequestDataSet()->getAttributeValue('key'));
    }

    public function testGetAttributeReturnNull(): void
    {
        $this->assertNull($this->createRequestDataSet()->getAttributeValue('non-exist'));
    }

    public function testHasAttribute(): void
    {
        $dataSet = $this->createRequestDataSet();
        $this->assertTrue($dataSet->hasAttribute('key'));
        $this->assertFalse($dataSet->hasAttribute('non-exist'));
    }

    private function createRequestDataSet(): RequestDataSet
    {
        return new RequestDataSet(['key' => 'value']);
    }
}
