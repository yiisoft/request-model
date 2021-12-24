<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel;

use Psr\Http\Message\ServerRequestInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionType;
use ReflectionUnionType;
use Yiisoft\Injector\Injector;
use Yiisoft\Router\CurrentRoute;
use Yiisoft\Validator\ValidatorInterface;

final class RequestModelFactory
{
    private Injector $injector;
    private ValidatorInterface $validator;
    private CurrentRoute $currentRoute;

    public function __construct(ValidatorInterface $validator, Injector $injector, CurrentRoute $currentRoute)
    {
        $this->validator = $validator;
        $this->injector = $injector;
        $this->currentRoute = $currentRoute;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ReflectionParameter[] $handlerParameters
     *
     * @throws ReflectionException
     *
     * @return array
     */
    public function createInstances(ServerRequestInterface $request, array $handlerParameters): array
    {
        $requestModelInstances = [];
        foreach ($this->getModelRequestClasses($handlerParameters) as $modelClass) {
            $requestModelInstances[] = $this->processModel($request, $this->injector->make($modelClass));
        }

        return $requestModelInstances;
    }

    private function processModel(ServerRequestInterface $request, RequestModelInterface $model): RequestModelInterface
    {
        $requestData = $this->getRequestData($request);
        $model->setRequestData($requestData);
        if ($model instanceof RulesProviderInterface) {
            $result = $this->validator->validate($model, $model->getRules());
            if (!$result->isValid()) {
                throw new RequestValidationException($result->getErrors());
            }
        }

        return $model;
    }

    /**
     * @param ReflectionParameter[] $handlerParameters
     *
     * @throws ReflectionException
     *
     * @psalm-return class-string<RequestModelInterface>[]
     *
     * @return string[]
     */
    private function getModelRequestClasses(array $handlerParameters): array
    {
        $modelClasses = [];
        foreach ($handlerParameters as $parameter) {
            if ($this->parameterIsRequestModel($parameter, $parameterType)) {
                /** @var ReflectionNamedType $parameterType */
                $parameterName = $parameterType->getName();
                /** @var class-string<RequestModelInterface> $parameterName */
                $modelClasses[] = $parameterName;
            }
        }

        return $modelClasses;
    }

    /**
     * @param ReflectionParameter $parameter
     * @param ReflectionType $parameterType
     *
     * @psalm-suppress UndefinedClass
     *
     * @throws ReflectionException
     *
     * @return bool
     */
    private function parameterIsRequestModel(
        ReflectionParameter $parameter,
        ReflectionType &$parameterType = null
    ): bool {
        if (!$parameter->hasType()) {
            return false;
        }
        /** @var ReflectionNamedType|ReflectionUnionType $reflectionType */
        $reflectionType = $parameter->getType();

        /** @var ReflectionNamedType[] $types */
        $types = $reflectionType instanceof ReflectionNamedType ? [$reflectionType] : $reflectionType->getTypes();

        foreach ($types as $type) {
            if (
                !$type->isBuiltin()
                && (new ReflectionClass($type->getName()))->implementsInterface(RequestModelInterface::class)
            ) {
                $parameterType = $type;
                return true;
            }
        }
        return false;
    }

    private function getRequestData(ServerRequestInterface $request): array
    {
        return [
            'query' => $request->getQueryParams(),
            'body' => $request->getParsedBody(),
            'attributes' => $request->getAttributes(),
            'headers' => $request->getHeaders(),
            'files' => $request->getUploadedFiles(),
            'cookie' => $request->getCookieParams(),
            'router' => $this->currentRoute->getArguments(),
        ];
    }
}
