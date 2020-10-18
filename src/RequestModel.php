<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel;

use Yiisoft\Arrays\ArrayHelper;

abstract class RequestModel implements RequestModelInterface
{
    private array $requestData = [];

    public function setRequestData(array $requestData): void
    {
        $this->requestData = $requestData;
    }

    public function getValue(string $field, $default = null)
    {
        return ArrayHelper::getValueByPath($this->requestData, $field, $default);
    }

    public function hasValue(string $field): bool
    {
        return $this->getValue($field) !== null;
    }

    public function getRequestData(): array
    {
        return $this->requestData;
    }
}
