<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Tests;

use Yiisoft\RequestModel\ActionWrapper;
use Yiisoft\RequestModel\Tests\Support\SimpleController;
use Yiisoft\RequestModel\Tests\Support\TestCase;

class ActionWrapperTest extends TestCase
{
    public function testCorrectProcess(): void
    {
        $container = $this->createContainer();

        $wrapper = new ActionWrapper(
            $container,
            $this->createRequestModelFactory($container),
            SimpleController::class,
            'action'
        );

        $request = $this->createRequest(
            [
                'login' => 'login',
                'password' => 'password',
            ]
        );

        $result = $wrapper->process($request, $this->createRequestHandler());

        $this->assertEquals(
            [
                ['login'],
                ['password'],
            ],
            $result->getHeaders()
        );
    }

    public function testCorrectProcessRouterParams(): void
    {
        $container = $this->createContainer();

        $wrapper = new ActionWrapper(
            $container,
            $this->createRequestModelFactory($container),
            SimpleController::class,
            'anotherAction'
        );

        $request = $this->createRequest(
            [
                'login' => 'login',
                'password' => 'password',
            ]
        );

        $result = $wrapper->process($request, $this->createRequestHandler());

        $this->assertEquals(
            [
                'id' => [1]
            ],
            $result->getHeaders()
        );
    }
}
