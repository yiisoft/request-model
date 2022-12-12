<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Attribute;

use Attribute;
use Psr\Http\Message\ServerRequestInterface;

#[Attribute(flags: Attribute::TARGET_PARAMETER | Attribute::IS_REPEATABLE)]
final class Query implements HandlerParameterAttributeInterface
{
    public function __construct(private ?string $name)
    {
    }

    public function resolve(ServerRequestInterface $request): mixed
    {
        if ($this->name !== null) {
            return $request->getQueryParams()[$this->name] ?? null;
        }

        return $request->getQueryParams();
    }
}
