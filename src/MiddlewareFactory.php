<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel;

use InvalidArgumentException;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Yiisoft\Middleware\Dispatcher\MiddlewareFactoryInterface;

final class MiddlewareFactory implements MiddlewareFactoryInterface
{
    private ContainerInterface $container;
    private WrapperFactory $wrapperFactory;

    public function __construct(ContainerInterface $container, WrapperFactory $wrapperFactory)
    {
        $this->container = $container;
        $this->wrapperFactory = $wrapperFactory;
    }

    public function create($middlewareDefinition): MiddlewareInterface
    {
        return $this->createMiddleware($middlewareDefinition);
    }

    /**
     * @param callable|string|array $middlewareDefinition
     * @return MiddlewareInterface
     */
    private function createMiddleware($middlewareDefinition): MiddlewareInterface
    {
        $this->validateMiddleware($middlewareDefinition);

        if (is_string($middlewareDefinition)) {
            return $this->container->get($middlewareDefinition);
        }

        if (is_array($middlewareDefinition) && !is_object($middlewareDefinition[0])) {
            return $this->wrapperFactory->createActionWrapper(...$middlewareDefinition);
        }

        return $this->wrapperFactory->createCallableWrapper($middlewareDefinition);
    }

    /**
     * @param callable|string|array $middlewareDefinition
     */
    private function validateMiddleware($middlewareDefinition): void
    {
        if (is_string($middlewareDefinition) && is_subclass_of($middlewareDefinition, MiddlewareInterface::class)) {
            return;
        }

        if ($this->isCallable($middlewareDefinition) && (!is_array($middlewareDefinition) || !is_object($middlewareDefinition[0]))) {
            return;
        }

        throw new InvalidArgumentException('Parameter should be either PSR middleware class name or a callable.');
    }

    private function isCallable($definition): bool
    {
        if (is_callable($definition)) {
            return true;
        }

        return is_array($definition) && array_keys($definition) === [0, 1] && in_array($definition[1], class_exists($definition[0]) ? get_class_methods($definition[0]) : [], true);
    }
}
