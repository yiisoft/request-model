<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ReflectionClass;
use Yiisoft\Injector\Injector;

final class ActionWrapper implements MiddlewareInterface
{
    public function __construct(
        private ContainerInterface $container,
        private HandlerParametersResolver $parametersResolver,
        private string $class,
        private string $method
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $controller = $this->container->get($this->class);
        $parameters = array_merge(
            [$request, $handler],
            $this->parametersResolver->resolve($this->getHandlerParameters(), $request),
        );
        return (new Injector($this->container))->invoke([$controller, $this->method], $parameters);
    }

    /**
     * @throws \ReflectionException
     * @return \ReflectionParameter[]
     */
    private function getHandlerParameters(): array
    {
        return (new ReflectionClass($this->class))
            ->getMethod($this->method)
            ->getParameters();
    }
}
