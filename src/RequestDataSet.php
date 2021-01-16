<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel;

use Yiisoft\Arrays\ArrayHelper;
use Yiisoft\Validator\DataSetInterface;

final class RequestDataSet implements DataSetInterface
{
    private array $requestData;

    public function __construct(array $requestData)
    {
        $this->requestData = $requestData;
    }

    public function getAttributeValue(string $attribute)
    {
        return ArrayHelper::getValueByPath($this->requestData, $attribute, null);
    }

    public function hasAttribute(string $attribute): bool
    {
        return $this->getAttributeValue($attribute) !== null;
    }
}
