<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel;

use Yiisoft\Validator\DataSetInterface;

interface RequestModelInterface extends DataSetInterface
{
    public function setRequestData(array $requestData): void;
}
