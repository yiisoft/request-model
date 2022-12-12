<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Attribute;

use Attribute;
use Psr\Http\Message\ServerRequestInterface;

#[Attribute(flags: Attribute::TARGET_PARAMETER)]
final class Body implements HandlerParameterAttributeInterface
{
    public function __construct(private ?string $name)
    {
    }

    public function resolve(ServerRequestInterface $request): array|object|null
    {
        if ($this->name !== null) {
            return $request->getParsedBody()[$this->name] ?? null;
        }

        return $request->getParsedBody();
    }
}
