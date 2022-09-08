<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel;

use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Yiisoft\Middleware\Dispatcher\WrapperFactoryInterface;

final class WrapperFactory implements WrapperFactoryInterface
{
    public function __construct(
        private ContainerInterface $container,
        private HandlerParametersResolver $parametersResolver
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function create($callable): MiddlewareInterface
    {
        if (is_array($callable)) {
            return new ActionWrapper($this->container, $this->parametersResolver, $callable[0], $callable[1]);
        }
        return new CallableWrapper($this->container, $this->parametersResolver, $callable);
    }
}
