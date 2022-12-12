<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Attribute;

use Attribute;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Router\CurrentRoute;

#[Attribute(flags: Attribute::TARGET_PARAMETER | Attribute::IS_REPEATABLE)]
final class Route implements HandlerParameterAttributeInterface
{
    public function __construct(private string $name)
    {
    }

    public function resolve(ServerRequestInterface $request): mixed
    {
        /** @var CurrentRoute|null $currentRoute */
        $currentRoute = $request->getAttribute(CurrentRoute::class);

        return $currentRoute?->getArgument($this->name);
    }
}
