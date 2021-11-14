<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Tests\Support;

use Nyholm\Psr7\Response;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Yiisoft\Http\Method;
use Yiisoft\Injector\Injector;
use Yiisoft\RequestModel\RequestModelFactory;
use Yiisoft\Router\CurrentRoute;
use Yiisoft\Router\CurrentRouteInterface;
use Yiisoft\Test\Support\Container\SimpleContainer;
use Yiisoft\Validator\Validator;

abstract class TestCase extends BaseTestCase
{
    public function createContainer(): ContainerInterface
    {
        return new SimpleContainer(
            [
                SimpleMiddleware::class => new SimpleMiddleware(),
                SimpleController::class => new SimpleController(),
                CurrentRouteInterface::class => $this->getCurrentRoute(),
            ]
        );
    }

    public function createRequestHandler(): RequestHandlerInterface
    {
        $requestHandler = $this->createMock(RequestHandlerInterface::class);
        $requestHandler
            ->method('handle')
            ->willReturn(new Response(200));

        return $requestHandler;
    }

    public function createRequest(array $body = []): ServerRequest
    {
        return (new ServerRequest(Method::POST, '/'))->withParsedBody($body);
    }

    public function createRequestModelFactory(ContainerInterface $container): RequestModelFactory
    {
        return new RequestModelFactory(new Validator(null), new Injector($container), $this->getCurrentRoute());
    }

    private function getCurrentRoute(): CurrentRouteInterface
    {
        $currentRoute = new CurrentRoute();
        $currentRoute->setParameters(['id' => 1]);
        return $currentRoute;
    }
}
