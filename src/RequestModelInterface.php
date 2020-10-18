<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel;

interface RequestModelInterface
{
    public function setRequestData(array $requestData): void;
}
