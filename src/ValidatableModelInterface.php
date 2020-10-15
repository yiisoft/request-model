<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel;

interface ValidatableModelInterface
{
    public function getRules(): array;
}
