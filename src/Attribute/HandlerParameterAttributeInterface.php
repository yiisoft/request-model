<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Attribute;

/**
 * Represents action handler parameter [attribute](https://www.php.net/manual/en/language.attributes.php).
 */
interface HandlerParameterAttributeInterface
{
    public const ROUTE_PARAM = 'route_param';
    public const REQUEST_BODY = 'request_body';
    public const REQUEST_ATTRIBUTE = 'request_attribute';
    public const UPLOADED_FILES = 'uploaded_files';
    public const QUERY_PARAM = 'query_param';

    public function getName(): ?string;

    public function getType(): string;
}
