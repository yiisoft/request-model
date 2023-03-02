<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Tests;

use Nyholm\Psr7\ServerRequest;
use ReflectionClass;
use ReflectionFunction;
use Yiisoft\RequestModel\RequestValidationException;
use Yiisoft\RequestModel\Tests\Support\SimpleController;
use Yiisoft\RequestModel\Tests\Support\SimpleRequestModel;
use Yiisoft\RequestModel\Tests\Support\SimpleValidationRequestModel;
use Yiisoft\RequestModel\Tests\Support\TestCase;

class RequestModelFactoryTest extends TestCase
{
    public function testCorrectCreateInstanceRequestModel(): void
    {
        $factory = $this->createRequestModelFactory($this->createContainer());
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

    public function dataNonProcessParameters(): array
    {
        return [
            'union-type' => ['actionUnionType'],
            'abstract-request-model' => ['actionAbstractRequestModel'],
            'request-model-interface' => ['actionRequestModelInterface'],
        ];
    }

    /**
     * @dataProvider dataNonProcessParameters
     */
    public function testNonProcessParameters(string $action): void
    {
        $factory = $this->createRequestModelFactory();

        $parameters = (new ReflectionClass(SimpleController::class))->getMethod($action)->getParameters();

        $instances = $factory->createInstances($this->createServerRequest(), $parameters);

        $this->assertSame([], $instances);
    }
}
