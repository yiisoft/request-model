<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Tests;

use Nyholm\Psr7\ServerRequest;
use ReflectionFunction;
use Yiisoft\Injector\Injector;
use Yiisoft\RequestModel\RequestModelFactory;
use Yiisoft\RequestModel\RequestValidationException;
use Yiisoft\RequestModel\Tests\Support\SimpleRequestModel;
use Yiisoft\RequestModel\Tests\Support\SimpleValidationRequestModel;
use Yiisoft\RequestModel\Tests\Support\TestCase;
use Yiisoft\RequestModel\YiiRouter\RouterDataProvider;
use Yiisoft\Validator\Validator;

class RequestModelFactoryTest extends TestCase
{
    public function testCorrectCreateInstanceRequestModel(): void
    {
        $factory = new RequestModelFactory(
            new Validator(),
            new Injector($this->createContainer()),
            [
                new RouterDataProvider($this->getCurrentRoute()),
            ],
        );
        $request = $this->createServerRequest(
            [
                'login' => 'login',
                'password' => 'password',
            ]
        );
        $params = (new ReflectionFunction(fn (SimpleRequestModel $requestModel) => ''))->getParameters();
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

    public function testCorrectlyIfNotFoundRequestModel(): void
    {
        $factory = $this->createRequestModelFactory($this->createContainer());
        $params = (new ReflectionFunction(fn (ServerRequest $request) => ''))->getParameters();

        $this->assertEmpty($factory->createInstances($this->createServerRequest(), $params));
    }

    public function testValidationInvalidRequestModel(): void
    {
        $this->expectException(RequestValidationException::class);
        $this->expectExceptionMessage('Request model validation error');

        $factory = $this->createRequestModelFactory($this->createContainer());
        $params = (new ReflectionFunction(fn (SimpleValidationRequestModel $requestModel) => ''))->getParameters();

        $factory->createInstances($this->createServerRequest(), $params);
    }

    public function testValidationCorrectRequestModel(): void
    {
        $factory = $this->createRequestModelFactory($this->createContainer());
        $request = $this->createServerRequest(
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
