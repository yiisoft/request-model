<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel;

use Yiisoft\Arrays\ArrayHelper;

abstract class RequestModel implements RequestModelInterface
{
    private array $requestData = [];
    protected string $attributeDelimiter = '.';

    public function setRequestData(array $requestData): void
    {
        $this->requestData = $requestData;
    }

    /**
     * @param string $attribute
     * @param mixed $default
     *
     * @return mixed
     */
    public function getAttributeValue(string $attribute, $default = null)
    {
        return ArrayHelper::getValueByPath($this->requestData, $attribute, $default, $this->attributeDelimiter);
    }

    public function hasAttribute(string $attribute): bool
    {
        return ArrayHelper::pathExists($this->requestData, $attribute, true, $this->attributeDelimiter);
    }

    public function getRequestData(): array
    {
        return $this->requestData;
    }
}
