<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Tests;

use Yiisoft\RequestModel\RequestModel;
use Yiisoft\RequestModel\Tests\Support\SimpleRequestModel;
use Yiisoft\RequestModel\Tests\Support\TestCase;

class RequestModelTest extends TestCase
{
    public function testGetValueMethod(): void
    {
        $model = $this->createRequestModel();

        $this->assertEquals('login', $model->getLogin());
        $this->assertEquals('login', $model->getAttributeValue('body.login'));
        $this->assertNull($model->getAttributeValue('query.login'));
    }

    public function testHasValueMethod(): void
    {
        $model = $this->createRequestModel();
        $this->assertTrue($model->hasAttribute('body.login'));
        $this->assertFalse($model->hasAttribute('query.login'));
    }

    public function testGetRequestDataMethod(): void
    {
        $this->assertEquals(
            [
                'query' => [],
                'body' => [
                    'login' => 'login',
                    'password' => 'password',
                ],
                'attributes' => [],
                'headers' => [],
                'files' => [],
                'cookie' => [],
            ],
            $this->createRequestModel()->getRequestData()
        );
    }

    public function testWithAttributeDelimiterMethod(): void
    {
        $model = new class extends RequestModel {
            public function getPrimaryName(): string
            {
                return $this->getAttributeValue('body->name.primary');
            }
        };

        $model = $model->withAttributeDelimiter('->');
        $model->setRequestData(
            [
                'body' => [
                    'name.primary' => 'mike',
                ],
            ],
        );

        $this->assertEquals('mike', $model->getPrimaryName());
    }

    private function createRequestModel(): SimpleRequestModel
    {
        $model = new SimpleRequestModel();
        $model->setRequestData(
            [
                'query' => [],
                'body' => [
                    'login' => 'login',
                    'password' => 'password',
                ],
                'attributes' => [],
                'headers' => [],
                'files' => [],
                'cookie' => [],
            ],
        );

        return $model;
    }
}
