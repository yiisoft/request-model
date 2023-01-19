<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Middleware\Dispatcher\ParametersResolverInterface;
use Yiisoft\RequestModel\Attribute\HandlerParameterAttributeInterface;
use Yiisoft\RequestModel\Attribute\HandlerParameterResolverInterface;

final class HandlerParametersResolver implements ParametersResolverInterface
{
    public function __construct(private RequestModelFactory $factory, private ContainerInterface $container)
    {
    }

    /**
     * {@inheritDoc}
     *
     * @throws \ReflectionException
     */
    public function resolve(array $parameters, ServerRequestInterface $request): array
    {
        return array_merge(
            $this->getAttributeParams($parameters, $request),
            $this->factory->createInstances($request, $parameters)
        );
    }

    /**
     * @param \ReflectionParameter[] $parameters
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function getAttributeParams(array $parameters, ServerRequestInterface $request): array
    {
        $actionParameters = [];
        foreach ($parameters as $parameter) {
            $attributes = $parameter->getAttributes(
                HandlerParameterAttributeInterface::class,
                \ReflectionAttribute::IS_INSTANCEOF
            );
            foreach ($attributes as $attribute) {
                /** @var HandlerParameterAttributeInterface $attributeInstance */
                $attributeInstance = $attribute->newInstance();
                $resolver = $this->container->get($attributeInstance->getResolverClassName());
                if (!($resolver instanceof HandlerParameterResolverInterface)) {
                    throw new \RuntimeException(
                        sprintf(
                            'Resolver "%s" should implement %s.',
                            $resolver::class,
                            HandlerParameterResolverInterface::class
                        )
                    );
                }

                $resolvedParameter = $resolver->resolve($attributeInstance, $request);
                if ($resolvedParameter === null && $parameter->isDefaultValueAvailable()) {
                    $resolvedParameter = $parameter->getDefaultValue();
                }
                $actionParameters[$parameter->getName()] = $resolvedParameter;
            }
        }
        return $actionParameters;
    }
}
