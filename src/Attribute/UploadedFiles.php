<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Attribute;

use Attribute;

#[Attribute(flags: Attribute::TARGET_PARAMETER)]
final class UploadedFiles implements HandlerParameterAttributeInterface
{
    public function getType(): string
    {
        return self::UPLOADED_FILES;
    }

    public function getName(): ?string
    {
        return null;
    }
}
