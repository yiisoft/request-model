<?php

declare(strict_types=1);

namespace Yiisoft\Yii\RequestModel;

interface ValidatableModelInterface
{
    public function getRules(): array;
}
