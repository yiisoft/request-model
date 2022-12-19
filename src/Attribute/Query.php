<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Attribute;

use Attribute;

#[Attribute(flags: Attribute::TARGET_PARAMETER | Attribute::IS_REPEATABLE)]
final class Query implements HandlerParameterAttributeInterface
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
        return QueryResolver::class;
    }
}
