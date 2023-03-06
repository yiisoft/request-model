<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Concept\RequestModel\Attribute;

use Psr\Http\Message\ServerRequestInterface;

interface RequestAttributeResolverInterface
{
    /**
     * @throws ValueNotFoundException When value for {@see $attribute} not found.
     */
    public function resolve(RequestAttributeInterface $attribute, ServerRequestInterface $request): mixed;
}
