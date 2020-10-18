<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Yiisoft\Injector\Injector;
use ReflectionClass;

final class ActionWrapper implements MiddlewareInterface
{
    private string $class;
    private string $method;
    private ContainerInterface $container;
    private RequestModelFactory $factory;

    public function __construct(ContainerInterface $container, RequestModelFactory $factory, string $class, string $method)
    {
        $this->container = $container;
        $this->factory = $factory;
        $this->class = $class;
        $this->method = $method;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $controller = $this->container->get($this->class);
        $params = array_merge([$request, $handler], $this->factory->createInstances($request, $this->getHandlerParams()));
        return (new Injector($this->container))->invoke([$controller, $this->method], $params);
    }

    private function getHandlerParams(): array
    {
        return (new ReflectionClass($this->class))->getMethod($this->method)->getParameters();
    }
}
