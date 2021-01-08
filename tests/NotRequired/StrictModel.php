<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Tests\NotRequired;

use Yiisoft\RequestModel\RequestModel;
use Yiisoft\RequestModel\ValidatableModelInterface;
use Yiisoft\Validator\Result;

final class StrictModel extends RequestModel implements ValidatableModelInterface
{
    public function getSort(): ?string
    {
        $sort = $this->getValue('query.sort');
        return empty($sort) ? null : $sort;
    }

    public function getRules(): array
    {
        return [
            'query.sort' => [
                function ($value) {
                    $result = new Result();
                    if ($value === null) {
                        return $result;
                    }

                    if (!in_array($value, ['asc', 'desc'])) {
                        $result->addError('Incorrect value');
                    }

                    return $result;
                },
            ],
        ];
    }
}
