<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;

final class WrapperFactory
{
    private ContainerInterface $container;
    private RequestModelFactory $requestModelFactory;

    public function __construct(ContainerInterface $container, RequestModelFactory $requestModelFactory)
    {
        $this->container = $container;
        $this->requestModelFactory = $requestModelFactory;
    }

    public function createCallableWrapper(callable $callback): MiddlewareInterface
    {
        return new CallableWrapper($this->container, $this->requestModelFactory, $callback);
    }

    /**
     * @psalm-param class-string $class
     */
    public function createActionWrapper(string $class, string $method): MiddlewareInterface
    {
        return new ActionWrapper($this->container, $this->requestModelFactory, $class, $method);
    }
}
