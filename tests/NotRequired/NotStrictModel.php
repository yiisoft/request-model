<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Tests\NotRequired;

use Yiisoft\RequestModel\RequestModel;
use Yiisoft\RequestModel\ValidatableModelInterface;
use Yiisoft\Validator\Rule\InRange;

final class NotStrictModel extends RequestModel implements ValidatableModelInterface
{
    public function getSort(): ?string
    {
        $sort = $this->getValue('query.sort');
        return in_array($sort, ['asc', 'desc']) ? $sort : null;
    }

    public function getRules(): array
    {
        return [
            'query.sort' => [
                (new InRange(['asc', 'desc']))->skipOnEmpty(true),
            ],
        ];
    }
}
