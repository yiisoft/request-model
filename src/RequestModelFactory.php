<?php

declare(strict_types=1);

namespace Yiisoft\Yii\RequestModel;

use App\Validation\RequestModelInterface;
use App\Validation\RequestModelValidator;
use App\Validation\RequestValidationException;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionParameter;
use Yiisoft\Injector\Injector;
use ReflectionException;

final class RequestModelFactory
{
    private Injector $injector;
    private RequestModelValidator $validator;

    public function __construct(RequestModelValidator $validator, Injector $factory)
    {
        $this->validator = $validator;
        $this->injector = $factory;
    }

    /**
     * @param ServerRequestInterface $request
     * @param array $handlerParams
     * @return array
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
        if ($model instanceof ValidatableModelInterface) {
            $this->validateRequest($model, $requestData);
        }

        return $model;
    }

    private function getModelRequestClasses(array $handlerParams): array
    {
        $modelClasses = [];
        foreach ($handlerParams as $param) {
            if ($this->paramsIsRequestModel($param)) {
                $modelClasses[] = $param->getClass()->getName();
            }
        }

        return $modelClasses;
    }

    private function paramsIsRequestModel(ReflectionParameter $param): bool
    {
        return $param->getClass()->implementsInterface(RequestModelInterface::class);
    }

    private function getRequestData(ServerRequestInterface $request): array
    {
        return [
            'query' => $request->getQueryParams(),
            'body' => $request->getParsedBody(),
            'attributes' => $request->getAttributes(),
            'headers' => $request->getHeaders(),
            'files' => $request->getUploadedFiles()
        ];
    }

    private function validateRequest(ValidatableModelInterface $model, array $requestData): void
    {
        $errors = $this->validator->validate($requestData, $model->getRules());
        if (!empty($errors)) {
            throw new RequestValidationException($errors);
        }
    }
}
