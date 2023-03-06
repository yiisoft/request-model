<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Concept\RequestModel;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Injector\Injector;
use Yiisoft\RequestModel\Concept\Model\ModelPopulator;
use Yiisoft\Router\CurrentRoute;

final class RequestModelFactory
{
    private Injector $injector;
    private RequestAttributesResolver $requestAttributesResolver;

    public function __construct(
        private ModelPopulator $modelPopulator,
        private CurrentRoute $currentRoute,
        ContainerInterface $container,
        private ?RequestModelProcessorInterface $processor
    ) {
        $this->injector = new Injector($container);
        $this->requestAttributesResolver = new RequestAttributesResolver($container);
    }

    /**
     * @psalm-template T as RequestModelInterface
     *
     * @psalm-param class-string<T> $className
     *
     * @psalm-return T
     */
    public function create(
        string $className,
        ServerRequestInterface $request
    ): object {
        /** @todo Получить аргументы конструктора, используя атрибуты */
        $model = $this->injector->make($className);

        $requestData = $this->getRequestData($request);


        $this->modelPopulator->populate($model, $data);

        $this->processor?->process($model);

        return $model;
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
