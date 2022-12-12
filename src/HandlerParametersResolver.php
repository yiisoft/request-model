<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel;

use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\RequestModel\Attribute\HandlerParameterAttributeInterface;
use Yiisoft\Router\CurrentRoute;

/**
 * @internal
 */
final class HandlerParametersResolver
{
    public function __construct(private RequestModelFactory $factory, private CurrentRoute $currentRoute)
    {
    }

    /**
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

                $resolvedParameter = $attributeInstance->resolve($request);
                if ($resolvedParameter === null && $parameter->isDefaultValueAvailable()) {
                    $resolvedParameter = $parameter->getDefaultValue();
                }
                $actionParameters[$parameter->getName()] = $resolvedParameter;
            }
        }
        return $actionParameters;
    }
}
