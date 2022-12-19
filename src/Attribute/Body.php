<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Attribute;

use Attribute;
use Psr\Http\Message\ServerRequestInterface;

#[Attribute(flags: Attribute::TARGET_PARAMETER)]
final class Body implements HandlerParameterAttributeInterface
{
    public function __construct(private ?string $name = null)
    {
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getResolverClassName(): string
    {
        return BodyResolver::class;
    }
}
