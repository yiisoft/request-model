<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel;

use Psr\Http\Message\ServerRequestInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionParameter;
use Yiisoft\Injector\Injector;
use Yiisoft\Router\CurrentRouteInterface;
use Yiisoft\Validator\ValidatorInterface;

final class RequestModelFactory
{
    private Injector $injector;
    private ValidatorInterface $validator;
    private CurrentRouteInterface $currentRoute;

    public function __construct(ValidatorInterface $validator, Injector $injector, CurrentRouteInterface $currentRoute)
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
        if ($model instanceof ValidatableModelInterface) {
            $result = $this->validator->validate($model, $model->getRules());
            if (!$result->isValid()) {
                throw new RequestValidationException($result->getErrors());
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
            if ($this->paramsIsRequestModel($param)) {
                $modelClasses[] = $param->getType()->getName();
            }
        }

        return $modelClasses;
    }

    private function paramsIsRequestModel(ReflectionParameter $param): bool
    {
        if (!$param->hasType() || $param->getType()->isBuiltin()) {
            return false;
        }

        return (new ReflectionClass($param->getType()->getName()))->implementsInterface(RequestModelInterface::class);
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
