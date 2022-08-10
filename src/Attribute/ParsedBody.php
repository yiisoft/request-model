<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Attribute;

use Attribute;

#[Attribute(flags: Attribute::TARGET_PARAMETER)]
final class ParsedBody implements HandlerParameterAttributeInterface
{
    public function getType(): string
    {
        return self::REQUEST_BODY;
    }

    public function getName(): ?string
    {
        return null;
    }
}
