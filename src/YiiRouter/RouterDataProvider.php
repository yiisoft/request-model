<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\YiiRouter;

use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\RequestModel\RequestDataProviderInterface;
use Yiisoft\Router\CurrentRoute;

final class RouterDataProvider implements RequestDataProviderInterface
{
    public function __construct(
        private CurrentRoute $currentRoute
    ) {
    }

    public function getData(ServerRequestInterface $request): array
    {
        return ['router' => $this->currentRoute->getArguments()];
    }
}
