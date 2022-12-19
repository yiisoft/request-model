<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Attribute;

use Psr\Http\Message\ServerRequestInterface;

final class QueryResolver implements HandlerParameterResolverInterface
{
    public function resolve(HandlerParameterAttributeInterface $attribute, ServerRequestInterface $request): mixed
    {
        if ($attribute::class !== Query::class) {
            throw new \InvalidArgumentException(sprintf('Expected "%s", got "%s".', Query::class, $attribute::class));
        }

        if ($attribute->getName() !== null) {
            return $request->getQueryParams()[$attribute->getName()] ?? null;
        }

        return $request->getQueryParams();
    }
}
