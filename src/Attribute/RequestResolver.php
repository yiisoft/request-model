<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Attribute;

use Psr\Http\Message\ServerRequestInterface;

final class RequestResolver implements HandlerParameterResolverInterface
{
    public function resolve(HandlerParameterAttributeInterface $attribute, ServerRequestInterface $request): mixed
    {
        if ($attribute::class !== Request::class) {
            throw new \InvalidArgumentException(sprintf('Expected "%s", got "%s".', Request::class, $attribute::class));
        }

        return $request->getAttribute($attribute->getName());
    }
}
