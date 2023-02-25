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
use Yiisoft\RequestModel\HandlerParametersResolver;
use Yiisoft\RequestModel\RequestModelFactory;
use Yiisoft\Router\CurrentRoute;
use Yiisoft\Router\Route;
use Yiisoft\Test\Support\Container\SimpleContainer;
use Yiisoft\Validator\Validator;

abstract class TestCase extends BaseTestCase
{
    public function createContainer(array $definitions = []): ContainerInterface
    {
        return new SimpleContainer(
            array_merge(
                [
                    SimpleMiddleware::class => new SimpleMiddleware(),
                    SimpleController::class => new SimpleController(),
                    CurrentRoute::class => $this->getCurrentRoute(),
                ],
                $definitions
            )
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

    public function createServerRequest(array $body = [], $files = []): ServerRequest
    {
        return (new ServerRequest(Method::POST, '/'))
            ->withParsedBody($body)
            ->withUploadedFiles($files);
    }

    public function createRequestModelFactory(ContainerInterface $container): RequestModelFactory
    {
        $validator = new Validator();
        return new RequestModelFactory($validator, new Injector($container));
    }

    public function createParametersResolver(ContainerInterface $container): HandlerParametersResolver
    {
        return new HandlerParametersResolver($this->createRequestModelFactory($container), $container);
    }

    protected function getCurrentRoute(): CurrentRoute
    {
        $currentRoute = new CurrentRoute();
        $currentRoute->setRouteWithArguments(Route::get('/{id}'), ['id' => '1']);
        return $currentRoute;
    }
}
