<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel;

use Psr\Http\Message\ServerRequestInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionParameter;
use Yiisoft\Injector\Injector;
use Yiisoft\Validator\Result;
use Yiisoft\Validator\ResultSet;
use Yiisoft\Validator\Validator;

final class RequestModelFactory
{
    private Injector $injector;

    public function __construct(Injector $injector)
    {
        $this->injector = $injector;
    }

    /**
     * @param ServerRequestInterface $request
     * @param array|ReflectionParameter[] $handlerParams
     *
     * @return array
     * @throws ReflectionException
     *
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
        ];
    }

    private function validateRequest(ValidatableModelInterface $model, array $requestData): void
    {
        $requestDataSet = new RequestDataSet($requestData);
        $result = $this->createValidator($model)->validate($requestDataSet);
        $errors = $this->getErrorsFromValidationResult($result);
        if (!empty($errors)) {
            throw new RequestValidationException($errors);
        }
    }

    private function createValidator(ValidatableModelInterface $model): Validator
    {
        return new Validator($model->getRules());
    }

    private function getErrorsFromValidationResult(ResultSet $result): array
    {
        /**
         * @var $fieldResult Result
         */
        $errors = [];
        foreach ($result->getIterator() as $field => $fieldResult) {
            if (!empty($fieldResult->getErrors())) {
                $errors[$field] = $fieldResult->getErrors();
            }
        }

        return $errors;
    }
}
