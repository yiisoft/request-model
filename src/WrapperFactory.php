<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;

final class WrapperFactory
{
    public function __construct(
        private ContainerInterface $container,
        private HandlerParametersResolver $parametersResolver
    ) {
    }

    public function createCallableWrapper(callable $callback): MiddlewareInterface
    {
        return new CallableWrapper($this->container, $this->parametersResolver, $callback);
    }

    public function createActionWrapper(string $class, string $method): MiddlewareInterface
    {
        return new ActionWrapper($this->container, $this->parametersResolver, $class, $method);
    }
}
