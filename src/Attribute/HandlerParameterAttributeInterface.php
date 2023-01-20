<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Attribute;

/**
 * Represents action handler parameter [attribute](https://www.php.net/manual/en/language.attributes.php).
 */
interface HandlerParameterAttributeInterface
{
    public function getResolverClassName(): string;
}
