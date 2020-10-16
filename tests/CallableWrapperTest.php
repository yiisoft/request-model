<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Tests;

use Nyholm\Psr7\Response;
use Yiisoft\RequestModel\Tests\Support\TestCase;
use Yiisoft\RequestModel\CallableWrapper;
use Yiisoft\RequestModel\Tests\Support\SimpleMiddleware;
use Yiisoft\RequestModel\Tests\Support\SimpleRequestModel;
use Closure;

class CallableWrapperTest extends TestCase
{
    public function testCorrectProcess(): void
    {
        $wrapper = $this->createWrapper(
            function (SimpleRequestModel $requestModel) {
                return new Response(400, [$requestModel->getLogin(), $requestModel->getPassword()]);
            }
        );

        $request = $this->createRequest(
            [
                'login' => 'login',
                'password' => 'password'
            ]
        );

        $result = $wrapper->process($request, $this->createRequestHandler());

        $this->assertEquals(400, $result->getStatusCode());
        $this->assertEquals(
            [
                ['login'],
                ['password']
            ],
            $result->getHeaders()
        );
    }

    public function testCorrectProcessIfCallbackReturnMiddleware(): void
    {
        $wrapper = $this->createWrapper(fn (SimpleRequestModel $requestModel) => new SimpleMiddleware());
        $result = $wrapper->process($this->createRequest(), $this->createRequestHandler());
        $this->assertEquals(200, $result->getStatusCode());
    }

    private function createWrapper(Closure $callback): CallableWrapper
    {
        $container = $this->createContainer();
        $requestModelFactory = $this->createRequestModelFactory($container);

        return new CallableWrapper($container, $requestModelFactory, $callback);
    }
}
