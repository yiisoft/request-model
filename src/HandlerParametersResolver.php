<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel;

use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\RequestModel\Attribute\HandlerParameterAttributeInterface;
use Yiisoft\Router\CurrentRoute;

/**
 * @internal
 */
class HandlerParametersResolver
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
     * @param ServerRequestInterface $request
     *
     * @return array
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

                $actionParameters[$parameter->getName()] = match ($attributeInstance->getType()) {
                    HandlerParameterAttributeInterface::ROUTE_PARAM => $this
                        ->currentRoute
                        ->getArgument($attributeInstance->getName()),
                    HandlerParameterAttributeInterface::REQUEST_BODY => $request->getParsedBody(),
                    HandlerParameterAttributeInterface::REQUEST_ATTRIBUTE => $request->getAttribute(
                        $attributeInstance->getName()
                    ),
                    HandlerParameterAttributeInterface::QUERY_PARAM => $request
                        ->getQueryParams()[$attributeInstance->getName()] ?? null,
                    HandlerParameterAttributeInterface::UPLOADED_FILES => $request->getUploadedFiles()
                };
            }
        }
        return $actionParameters;
    }
}
