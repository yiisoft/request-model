<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Tests\Support;

use Yiisoft\RequestModel\RequestModel;
use Yiisoft\RequestModel\StrictValidatableModelInterface;
use Yiisoft\Validator\Rule\InRange;

final class StrictOptionalRequestModel extends RequestModel implements StrictValidatableModelInterface
{
    public function getSort(): ?string
    {
        return $this->getValue('query.sort');
    }

    public function getRules(): array
    {
        return [
            'query.sort' => [
                (new InRange(['asc', 'desc'])),
            ],
        ];
    }

    public function getOptionalFields(): array
    {
        return ['query.sort'];
    }
}
