<?php

declare(strict_types=1);

use Yiisoft\Router\MiddlewareFactoryInterface;
use Yiisoft\RequestModel\RequestValidationMiddlewareFactory;

return [
    MiddlewareFactoryInterface::class => RequestValidationMiddlewareFactory::class,
];
