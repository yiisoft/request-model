<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Attribute;

/**
 * @internal
 */
final class NotFoundValue
{
    private static ?self $instance = null;

    public static function getInstance(): self
    {
        return self::$instance ??= new self();
    }
}
