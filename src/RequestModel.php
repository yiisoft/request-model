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

    /**
     * @param string $field
     * @param mixed $default
     *
     * @return mixed
     */
    public function getValue(string $field, $default = null)
    {
        if ($this->isOptionalField($field)) {
            $value = ArrayHelper::getValueByPath($this->requestData, $field);
            return empty($value) ? $default : $value;
        }

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

    public function getOptionalFields(): array
    {
        return [];
    }

    private function isOptionalField(string $field): bool
    {
        return in_array($field, $this->getOptionalFields());
    }
}
