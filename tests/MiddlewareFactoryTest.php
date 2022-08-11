<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Tests;

use Yiisoft\RequestModel\ActionWrapper;
use Yiisoft\RequestModel\CallableWrapper;
use Yiisoft\RequestModel\MiddlewareFactory;
use Yiisoft\RequestModel\Tests\Support\SimpleController;
use Yiisoft\RequestModel\Tests\Support\SimpleMiddleware;
use Yiisoft\RequestModel\Tests\Support\TestCase;
use Yiisoft\RequestModel\WrapperFactory;

class MiddlewareFactoryTest extends TestCase
{
    public function testCreateFromString(): void
    {
        $middleware = $this
            ->createMiddlewareFactory()
            ->create(SimpleMiddleware::class);
        $this->assertInstanceOf(SimpleMiddleware::class, $middleware);
    }

    public function testCreateFromArray(): void
    {
        $middleware = $this
            ->createMiddlewareFactory()
            ->create([SimpleController::class, 'action']);
        $this->assertInstanceOf(ActionWrapper::class, $middleware);
    }

    public function testCreateFromCallable(): void
    {
        $middleware = $this
            ->createMiddlewareFactory()
            ->create(fn () => '');
        $this->assertInstanceOf(CallableWrapper::class, $middleware);
    }

    public function testInvalidMiddleware(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this
            ->createMiddlewareFactory()
            ->create(new \stdClass());
    }

    private function createMiddlewareFactory(): MiddlewareFactory
    {
        $container = $this->createContainer();
        $parametersResolver = $this->createParametersResolver($container);
        $wrapperFactory = new WrapperFactory($container, $parametersResolver);
        return new MiddlewareFactory($container, $wrapperFactory);
    }
}
