<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel;

use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\RequestModel\Attribute\HandlerParameterInterface;
use Yiisoft\RequestModel\Attribute\RouteParam;
use Yiisoft\Router\CurrentRoute;

class HandlerParametersResolver
{
    public function __construct(private RequestModelFactory $factory, private CurrentRoute $currentRoute)
    {
    }

    public function resolve(array $parameters, ServerRequestInterface $request): array
    {
        return array_merge(
            $this->getAttributeParams($parameters, $request),
            $this->factory->createInstances($request, $parameters)
        );
    }

    private function getAttributeParams(array $parameters, ServerRequestInterface $request): array
    {
        $actionParams = [];
        foreach ($parameters as $parameter) {
            $attributes = $parameter->getAttributes(
                HandlerParameterInterface::class,
                \ReflectionAttribute::IS_INSTANCEOF
            );
            foreach ($attributes as $attribute) {
                /** @var RouteParam $attributeInstance */
                $attributeInstance = $attribute->newInstance();

                $actionParams[$parameter->getName()] = match ($attributeInstance->getType()) {
                    HandlerParameterInterface::ROUTE_PARAM => $this
                        ->currentRoute
                        ->getArgument($attributeInstance->getName()),
                    HandlerParameterInterface::REQUEST_BODY => $request->getParsedBody(),
                    HandlerParameterInterface::QUERY_PARAM => $request
                        ->getQueryParams()[$attributeInstance->getName()] ?? null,
                    HandlerParameterInterface::UPLOADED_FILES => $request->getUploadedFiles()
                };
            }
        }
        return $actionParams;
    }
}
