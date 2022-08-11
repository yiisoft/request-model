<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Tests;

use Nyholm\Psr7\Stream;
use Nyholm\Psr7\UploadedFile;
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
            $this->createParametersResolver($container),
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
            $this->createParametersResolver($container),
            SimpleController::class,
            'anotherAction'
        );

        $request = $this->createRequest([]);

        $result = $wrapper->process($request, $this->createRequestHandler());

        $this->assertEquals(
            [
                'id' => [1],
            ],
            $result->getHeaders()
        );
    }

    public function testCorrectProcessAttributes(): void
    {
        $container = $this->createContainer();

        $wrapper = new ActionWrapper(
            $container,
            $this->createParametersResolver($container),
            SimpleController::class,
            'actionUsingAttributes'
        );

        $body = [
            'test',
        ];
        $stream = Stream::create('test');
        $files = [new UploadedFile($stream, $stream->getSize(), UPLOAD_ERR_OK, 'test.txt')];
        $request = $this->createRequest($body);
        $request = $request->withUploadedFiles($files);

        $result = $wrapper->process($request, $this->createRequestHandler());

        $this->assertEquals(
            [
                'id' => [1],
                'body' => $body,
                'countFiles' => [1],
            ],
            $result->getHeaders()
        );
    }

    public function testCorrectProcessAttributes2(): void
    {
        $container = $this->createContainer();

        $wrapper = new ActionWrapper(
            $container,
            $this->createParametersResolver($container),
            SimpleController::class,
            'actionUsingAttributes2'
        );

        $request = $this->createRequest([]);
        $request = $request->withAttribute('attribute', 'test')->withQueryParams(['page' => 1]);

        $result = $wrapper->process($request, $this->createRequestHandler());

        $this->assertEquals(
            [
                'page' => [1],
                'attribute' => ['test'],
            ],
            $result->getHeaders()
        );
    }
}
