<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Tests;

use Yiisoft\RequestModel\ActionWrapper;
use Yiisoft\RequestModel\CallableWrapper;
use Yiisoft\RequestModel\Tests\Support\SimpleController;
use Yiisoft\RequestModel\Tests\Support\SimpleRequestModel;
use Yiisoft\RequestModel\Tests\Support\TestCase;
use Yiisoft\RequestModel\WrapperFactory;

class WrapperFactoryTest extends TestCase
{
    public function testCorrectCreateActionWrapper(): void
    {
        $factory = $this->createWrapperFactory();
        $result = $factory->createActionWrapper(SimpleController::class, 'action');
        $this->assertInstanceOf(ActionWrapper::class, $result);
    }

    public function testCorrectCreateCallableWrapper(): void
    {
        $factory = $this->createWrapperFactory();
        $result = $factory->createCallableWrapper(fn (SimpleRequestModel $request) => '');
        $this->assertInstanceOf(CallableWrapper::class, $result);
    }

    private function createWrapperFactory(): WrapperFactory
    {
        $container = $this->createContainer();
        $requestModelFactory = $this->createRequestModelFactory($container);
        return new WrapperFactory($container, $requestModelFactory);
    }
}
