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

    public function getAttributeValue(string $attribute, $default = null)
    {
        return ArrayHelper::getValueByPath($this->requestData, $attribute, $default);
    }

    public function hasAttribute(string $attribute): bool
    {
        return $this->getAttributeValue($attribute) !== null;
    }

    public function getRequestData(): array
    {
        return $this->requestData;
    }
}
