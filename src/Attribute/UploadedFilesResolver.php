<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Attribute;

use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Router\CurrentRoute;

final class UploadedFilesResolver implements HandlerParameterResolverInterface
{
    public function resolve(HandlerParameterAttributeInterface $attribute, ServerRequestInterface $request): array
    {
        if ($attribute::class !== UploadedFiles::class) {
            throw new \InvalidArgumentException(sprintf('Expected "%s", got "%s".', UploadedFiles::class, $attribute::class));
        }

        return $request->getUploadedFiles();
    }
}
