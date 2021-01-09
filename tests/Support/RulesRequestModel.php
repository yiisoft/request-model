<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Tests\Support;

use Yiisoft\RequestModel\RequestModel;
use Yiisoft\RequestModel\ValidatableModelInterface;

final class RulesRequestModel extends RequestModel implements ValidatableModelInterface
{
    private array $rules;

    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }

    public function getRules(): array
    {
        return $this->rules;
    }
}
