<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Tests;

use Yiisoft\RequestModel\Tests\Support\TestCase;
use Yiisoft\RequestModel\Tests\Support\SimpleRequestModel;

class RequestModelTest extends TestCase
{
    public function testGetValueMethod(): void
    {
        $model = $this->createRequestModel();

        $this->assertEquals('login', $model->getLogin());
        $this->assertEquals('login', $model->getValue('body.login'));
        $this->assertNull($model->getValue('query.login'));
    }

    public function testHasValueMethod(): void
    {
        $model = $this->createRequestModel();
        $this->assertTrue($model->hasValue('body.login'));
        $this->assertFalse($model->hasValue('query.login'));
    }

    public function testGetRequestDataMethod(): void
    {
        $this->assertEquals(
            [
                'query' => [],
                'body' => [
                    'login' => 'login',
                    'password' => 'password'
                ],
                'attributes' => [],
                'headers' => [],
                'files' => [],
                'cookie' => []
            ],
            $this->createRequestModel()->getRequestData()
        );
    }

    private function createRequestModel(): SimpleRequestModel
    {
        $model = new SimpleRequestModel();
        $model->setRequestData(
            [
                'query' => [],
                'body' => [
                    'login' => 'login',
                    'password' => 'password'
                ],
                'attributes' => [],
                'headers' => [],
                'files' => [],
                'cookie' => []
            ],
        );

        return $model;
    }
}
