<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Attribute;

use Attribute;

/**
 *
 */
#[Attribute(flags: Attribute::TARGET_PARAMETER | Attribute::IS_REPEATABLE)]
final class ReqAttribute implements HandlerParameterAttributeInterface
{
    public function __construct(private string $name)
    {
    }

    public function getType(): string
    {
        return self::REQUEST_ATTRIBUTE;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
}
