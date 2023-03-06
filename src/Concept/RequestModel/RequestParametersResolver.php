<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Concept\RequestModel;

use Psr\Http\Message\ServerRequestInterface;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionParameter;
use Yiisoft\Middleware\Dispatcher\ParametersResolverInterface;

final class RequestParametersResolver implements ParametersResolverInterface
{
    public function __construct(
        private RequestAttributesResolver $attributesResolver,
        private RequestModelFactory $requestModelFactory,
    ) {
    }

    public function resolve(array $parameters, ServerRequestInterface $request): array
    {
        $result = $this->attributesResolver->resolve($parameters, $request);

        foreach ($parameters as $parameter) {
            $parameterName = $parameter->getName();
            if (array_key_exists($parameterName, $result)) {
                continue;
            }

            $requestModelClass = $this->getRequestModelClass($parameter);
            if ($requestModelClass === null) {
                continue;
            }

            $result[$parameterName] = $this->requestModelFactory->create($requestModelClass, $request);
        }

        return $result;
    }

    /**
     * @psalm-return class-string<RequestModelInterface>|null
     */
    private function getRequestModelClass(ReflectionParameter $reflection): ?string
    {
        $type = $reflection->getType();
        if (
            !$type instanceof ReflectionNamedType
            || $type->isBuiltin()
        ) {
            return null;
        }

        $className = $type->getName();
        if (!$this->isInstantiableRequestModel($className)) {
            return null;
        }

        return $className;
    }

    /**
     * @psalm-param class-string $className
     * @psalm-assert-if-true class-string<RequestModelInterface> $className
     */
    private function isInstantiableRequestModel(string $className): bool
    {
        $reflectionClass = new ReflectionClass($className);

        return $reflectionClass->isInstantiable()
            && $reflectionClass->implementsInterface(RequestModelInterface::class);
    }
}
