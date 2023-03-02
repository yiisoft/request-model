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

        $parsedBody = $request->getParsedBody();

        if ($attribute->getName() !== null) {
            if (!is_array($parsedBody)) {
                throw new ValueNotFoundException();
            }

            return $parsedBody[$attribute->getName()] ?? throw new ValueNotFoundException();
        }

        return $parsedBody;
    }
}
