<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Tests;

use Nyholm\Psr7\Response;
use Yiisoft\RequestModel\Attribute\Body;
use Yiisoft\RequestModel\Attribute\Request;
use Yiisoft\RequestModel\Attribute\Route;
use Yiisoft\RequestModel\CallableWrapper;
use Yiisoft\RequestModel\Tests\Support\SimpleController;
use Yiisoft\RequestModel\Tests\Support\SimpleMiddleware;
use Yiisoft\RequestModel\Tests\Support\SimpleRequestModel;
use Yiisoft\RequestModel\Tests\Support\TestCase;

class CallableWrapperTest extends TestCase
{
    public function testCorrectProcessClosure(): void
    {
        $wrapper = $this->createWrapper(
            fn(SimpleRequestModel $requestModel) => new Response(400, [$requestModel->getLogin(), $requestModel->getPassword()])
        );

        $request = $this->createServerRequest(
            [
                'login' => 'login',
                'password' => 'password',
            ]
        );

        $result = $wrapper->process($request, $this->createRequestHandler());

        $this->assertEquals(400, $result->getStatusCode());
        $this->assertEquals(
            [
                ['login'],
                ['password'],
            ],
            $result->getHeaders()
        );
    }

    public function testCorrectProcessClosureWithAttributes(): void
    {
        $wrapper = $this->createWrapper(
            fn(#[Route('id')] int $id, #[Body] array $body, #[Request('foo')] string $foo) => new Response(400, ['id' => $id, 'body' => $body, 'foo' => $foo])
        );

        $request = $this->createServerRequest(['test'])->withAttribute('foo', 'bar');

        $result = $wrapper->process($request, $this->createRequestHandler());

        $this->assertEquals(400, $result->getStatusCode());
        $this->assertEquals(
            [
                'id' => [1],
                'body' => ['test'],
                'foo' => ['bar'],
            ],
            $result->getHeaders()
        );
    }

    public function testCorrectProcessStaticCallable(): void
    {
        $controller = new SimpleController();
        $wrapper = $this->createWrapper([$controller, 'action']);

        $request = $this->createServerRequest(
            [
                'login' => 'login',
                'password' => 'password',
            ]
        );

        $result = $wrapper->process($request, $this->createRequestHandler());

        $this->assertEquals(200, $result->getStatusCode());
        $this->assertEquals(
            [
                ['login'],
                ['password'],
            ],
            $result->getHeaders()
        );
    }

    public function testCorrectProcessCallableObject(): void
    {
        $obj = new class () {
            public function __invoke(SimpleRequestModel $request)
            {
                return (new SimpleController())->action($request);
            }
        };

        $wrapper = $this->createWrapper($obj);

        $request = $this->createServerRequest(
            [
                'login' => 'login',
                'password' => 'password',
            ]
        );

        $result = $wrapper->process($request, $this->createRequestHandler());

        $this->assertEquals(200, $result->getStatusCode());
        $this->assertEquals(
            [
                ['login'],
                ['password'],
            ],
            $result->getHeaders()
        );
    }

    public function testCorrectProcessIfCallbackReturnMiddleware(): void
    {
        $wrapper = $this->createWrapper(fn (SimpleRequestModel $requestModel) => new SimpleMiddleware());
        $result = $wrapper->process($this->createServerRequest(), $this->createRequestHandler());
        $this->assertEquals(200, $result->getStatusCode());
    }

    private function createWrapper(callable $callback): CallableWrapper
    {
        $container = $this->createContainer();
        $parametersResolver = $this->createParametersResolver($container);

        return new CallableWrapper($container, $parametersResolver, $callback);
    }
}
