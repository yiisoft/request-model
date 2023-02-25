<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\YiiRouter\Attribute;

use Attribute;
use Yiisoft\RequestModel\Attribute\HandlerParameterAttributeInterface;

#[Attribute(flags: Attribute::TARGET_PARAMETER | Attribute::IS_REPEATABLE)]
final class Route implements HandlerParameterAttributeInterface
{
    public function __construct(private string $name)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getResolverClassName(): string
    {
        return RouteResolver::class;
    }
}
