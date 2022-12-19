<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Attribute;

use Attribute;

#[Attribute(flags: Attribute::TARGET_PARAMETER)]
final class UploadedFiles implements HandlerParameterAttributeInterface
{
    public function getResolverClassName(): string
    {
        return UploadedFilesResolver::class;
    }
}
