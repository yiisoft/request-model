<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Attribute;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Represents action handler parameter [attribute](https://www.php.net/manual/en/language.attributes.php).
 */
interface HandlerParameterResolverInterface
{
    /**
     * @throws ValueNotFoundException When value for {@see $attribute} not found.
     */
    public function resolve(HandlerParameterAttributeInterface $attribute, ServerRequestInterface $request): mixed;
}
