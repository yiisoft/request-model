<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Tests;

use Closure;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionClass;
use ReflectionFunction;
use Yiisoft\RequestModel\Attribute\BodyResolver;
use Yiisoft\RequestModel\Attribute\Request;
use Yiisoft\RequestModel\Attribute\RequestResolver;
use Yiisoft\RequestModel\YiiRouter\Attribute\Route;
use Yiisoft\RequestModel\YiiRouter\Attribute\RouteResolver;
use Yiisoft\RequestModel\Attribute\UploadedFilesResolver;
use Yiisoft\RequestModel\Tests\Support\MockAttribute;
use Yiisoft\RequestModel\Tests\Support\MockHandler;
use Yiisoft\RequestModel\Tests\Support\SimpleController;
use Yiisoft\RequestModel\Tests\Support\SimpleRequestModel;
use Yiisoft\RequestModel\Tests\Support\TestCase;

class HandlerParametersResolverTest extends TestCase
{
    public function testCorrectResolveActionParameters(): void
    {
        $container = $this->createContainer();
        $resolver = $this->createParametersResolver($container);
        $result = $resolver->resolve(
            $this->getActionParameters([SimpleController::class, 'action']),
            $this->createMock(ServerRequestInterface::class)
        );
        $this->assertInstanceOf(SimpleRequestModel::class, $result[0]);
    }

    public function testCorrectResolveCallbackActionParameters(): void
    {
        $container = $this->createContainer();
        $resolver = $this->createParametersResolver($container);
        $result = $resolver->resolve(
            $this->getActionParameters(static fn (SimpleRequestModel $model) => ''),
            $this->createMock(ServerRequestInterface::class)
        );
        $this->assertInstanceOf(SimpleRequestModel::class, $result[0]);
    }

    public function testErrorResolveActionParameters(): void
    {
        $container = $this->createContainer([MockHandler::class => new MockHandler()]);
        $resolver = $this->createParametersResolver($container);

        $this->expectException(\RuntimeException::class);

        $resolver->resolve(
            $this->getActionParameters([SimpleController::class, 'actionWithWrongAttribute']),
            $this->createMock(ServerRequestInterface::class)
        );
    }

    public function testErrorResolveCallbackActionParameters(): void
    {
        $container = $this->createContainer([MockHandler::class => new MockHandler()]);
        $resolver = $this->createParametersResolver($container);

        $this->expectException(\RuntimeException::class);

        $resolver->resolve(
            $this->getActionParameters(static fn (#[MockAttribute] $page = 1) => ''),
            $this->createMock(ServerRequestInterface::class)
        );
    }

    public function testCorrectResolveCallableActionParametersWithAttributes(): void
    {
        $container = $this->createContainer([
            RouteResolver::class => new RouteResolver($this->getCurrentRoute()),
            RequestResolver::class => new RequestResolver(),
        ]);
        $resolver = $this->createParametersResolver($container);
        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getAttribute')->willReturn('foo');
        $result = $resolver->resolve(
            $this->getActionParameters(static fn (#[Route('id')] int $id, #[Request('test')] string $att) => ''),
            $request
        );
        $this->assertEquals(1, $result['id']);
    }

    public function testCorrectResolveActionParametersWithAttributes(): void
    {
        $container = $this->createContainer([
            RouteResolver::class => new RouteResolver($this->getCurrentRoute()),
            RequestResolver::class => new RequestResolver(),
            BodyResolver::class => new BodyResolver(),
            UploadedFilesResolver::class => new UploadedFilesResolver(),
        ]);
        $resolver = $this->createParametersResolver($container);
        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getUploadedFiles')->willReturn($files = [new \stdClass()]);
        $result = $resolver->resolve(
            $this->getActionParameters([SimpleController::class, 'actionUsingAttributes']),
            $request
        );
        $this->assertEquals(1, $result['id']);
        $this->assertSame($files, $result['files']);
    }

    /**
     * @param array{0: class-string, 1: string}|callable $action
     *
     * @throws \ReflectionException
     *
     * @return \ReflectionParameter[]
     */
    private function getActionParameters(callable|array $action): array
    {
        if (is_callable($action)) {
            $callable = Closure::fromCallable($action);
            return (new ReflectionFunction($callable))->getParameters();
        }

        return (new ReflectionClass($action[0]))->getMethod($action[1])->getParameters();
    }
}
