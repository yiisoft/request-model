<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Tests\Php8;

use Nyholm\Psr7\Request;
use Nyholm\Psr7\ServerRequest;
use ReflectionFunction;
use Yiisoft\RequestModel\Tests\Support\SimpleRequestModel;
use Yiisoft\RequestModel\Tests\Support\SimpleValidationRequestModel;
use Yiisoft\RequestModel\Tests\Support\TestCase;

class RequestModelFactoryTest extends TestCase
{
    public function testCorrectCreateInstanceRequestModelWithUnionTypes(): void
    {
        $factory = $this->createRequestModelFactory($this->createContainer());
        $request = $this->createRequest(
            [
                'login' => 'login',
                'password' => 'password',
            ]
        );
        $params = (new ReflectionFunction(fn (ServerRequest|SimpleRequestModel $requestModel) => ''))->getParameters();
        $result = $factory->createInstances($request, $params);

        $this->assertCount(1, $result);

        /**
         * @var $model SimpleRequestModel
         */
        $model = current($result);

        $this->assertEquals('login', $model->getLogin());
        $this->assertEquals('password', $model->getPassword());
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
                'router' => ['id' => 1],
            ],
            $model->getRequestData()
        );
    }

    public function testCorrectlyIfNotFoundRequestModelWithUnionTypes(): void
    {
        $factory = $this->createRequestModelFactory($this->createContainer());
        $params = (new ReflectionFunction(fn (ServerRequest|Request $request) => ''))->getParameters();

        $this->assertEmpty($factory->createInstances($this->createRequest(), $params));
    }

    public function testWithBuiltinUnionTypes(): void
    {
        $factory = $this->createRequestModelFactory($this->createContainer());
        $params = (new ReflectionFunction(fn (int|string $requestModel) => ''))->getParameters();

        $this->assertEmpty($factory->createInstances($this->createRequest(), $params));
    }

    public function testValidationCorrectRequestModel(): void
    {
        $factory = $this->createRequestModelFactory($this->createContainer());
        $request = $this->createRequest(
            [
                'login' => 'login',
                'password' => 'password',
            ]
        );

        $params = (new ReflectionFunction(fn (SimpleValidationRequestModel $requestModel) => ''))->getParameters();
        $result = $factory->createInstances($request, $params);

        $this->assertCount(1, $result);

        /**
         * @var $model SimpleValidationRequestModel
         */
        $model = current($result);

        $this->assertEquals('login', $model->getLogin());
        $this->assertEquals('password', $model->getPassword());
    }
}
