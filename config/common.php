<?php

declare(strict_types=1);

use Yiisoft\Router\MiddlewareFactoryInterface;
use Yiisoft\Yii\RequestModel\RequestValidationMiddlewareFactory;

return [
    MiddlewareFactoryInterface::class => RequestValidationMiddlewareFactory::class,
];
