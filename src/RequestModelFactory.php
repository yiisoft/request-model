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
     * @param array|ReflectionParameter[] $handlerParams
     *
     * @throws ReflectionException
     *
     * @return array
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
            $result = $this->validator->validate($model, $model->getRules());
            if (!$result->isValid()) {
                throw new RequestValidationException($result->getErrorMessagesIndexedByAttribute());
            }
        }

        return $model;
    }

    /**
     * @param array|ReflectionParameter[] $handlerParams
     *
     * @return array
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
        if (!$param->hasType() || $param->getType()->isBuiltin()) {
            return false;
        }

        /** @var ReflectionNamedType $type */
        $type = $param->getType();

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
