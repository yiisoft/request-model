<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Attribute;

interface HandlerParameterInterface
{
    public const ROUTE_PARAM = 'route_param';
    public const REQUEST_BODY = 'request_body';
    public const UPLOADED_FILES = 'uploaded_files';
    public const QUERY_PARAM = 'query_param';

    public function getName(): ?string;

    public function getType(): string;
}