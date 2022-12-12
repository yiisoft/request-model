<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Attribute;

use Attribute;
use Psr\Http\Message\ServerRequestInterface;

#[Attribute(flags: Attribute::TARGET_PARAMETER)]
final class UploadedFiles implements HandlerParameterAttributeInterface
{
    public function resolve(ServerRequestInterface $request): array
    {
        return $request->getUploadedFiles();
    }
}
