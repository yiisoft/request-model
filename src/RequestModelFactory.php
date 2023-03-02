<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel;

use Psr\Http\Message\ServerRequestInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;
use ReflectionParameter;
use Yiisoft\Injector\Injector;
use Yiisoft\Router\CurrentRoute;
use Yiisoft\Validator\RulesProviderInterface;
use Yiisoft\Validator\ValidatorInterface;

final class RequestModelFactory
{
    public function __construct(
        private ValidatorInterface $validator,
        private Injector $injector,
        private CurrentRoute $currentRoute
    ) {
    }

    /**
     * @param array|ReflectionParameter[] $handlerParams
     *
     * @throws ReflectionException
     */
    public function createInstances(ServerRequestInterface $request, array $handlerParams): array
    {
        $requestModelInstances = [];
        foreach ($this->getModelRequestClasses($handlerParams) as $modelClass) {
            $requestModelInstances[] = $this->processModel($request, $this->injector->make($modelClass));
        }

        return $requestModelInstances;
    }

    private function processModel(ServerRequestInterface $request, RequestModelInterface $model): RequestModelInterface
    {
        $requestData = $this->getRequestData($request);
        $model->setRequestData($requestData);
        if ($model instanceof RulesProviderInterface) {
            $result = $this->validator->validate($model);
            if (!$result->isValid()) {
                $errorMessagesIndexedByAttribute = $result->getErrorMessagesIndexedByAttribute();
                throw new RequestValidationException($errorMessagesIndexedByAttribute);
            }
        }

        return $model;
    }

    /**
     * @param array|ReflectionParameter[] $handlerParams
     */
    private function getModelRequestClasses(array $handlerParams): array
    {
        $modelClasses = [];
        foreach ($handlerParams as $param) {
            if (!$this->paramsIsRequestModel($param)) {
                continue;
            }

            /** @var ReflectionNamedType $type */
            $type = $param->getType();
            $modelClasses[] = $type->getName();
        }

        return $modelClasses;
    }

    private function paramsIsRequestModel(ReflectionParameter $param): bool
    {
        $type = $param->getType();
        if (
            !$type instanceof ReflectionNamedType
            || $type->isBuiltin()
        ) {
            return false;
        }

        return (new ReflectionClass($type->getName()))->implementsInterface(RequestModelInterface::class);
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
