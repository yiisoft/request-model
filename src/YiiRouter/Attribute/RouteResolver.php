<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\YiiRouter\Attribute;

use InvalidArgumentException;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\RequestModel\Attribute\HandlerParameterAttributeInterface;
use Yiisoft\RequestModel\Attribute\HandlerParameterResolverInterface;
use Yiisoft\Router\CurrentRoute;

final class RouteResolver implements HandlerParameterResolverInterface
{
    public function __construct(private CurrentRoute $currentRoute)
    {
    }

    public function resolve(HandlerParameterAttributeInterface $attribute, ServerRequestInterface $request): mixed
    {
        if ($attribute::class !== Route::class) {
            throw new InvalidArgumentException(sprintf('Expected "%s", got "%s".', Route::class, $attribute::class));
        }

        return $this->currentRoute->getArgument($attribute->getName());
    }
}
