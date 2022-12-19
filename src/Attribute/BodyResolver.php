<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Attribute;

use Psr\Http\Message\ServerRequestInterface;

final class BodyResolver implements HandlerParameterResolverInterface
{
    public function resolve(HandlerParameterAttributeInterface $attribute, ServerRequestInterface $request): mixed
    {
        if ($attribute::class !== Body::class) {
            throw new \InvalidArgumentException(sprintf('Expected "%s", got "%s".', Body::class, $attribute::class));
        }

        if ($attribute->getName() !== null) {
            return $request->getParsedBody()[$attribute->getName()] ?? null;
        }

        return $request->getParsedBody();
    }
}
