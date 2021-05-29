<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Tests\Support;

use Yiisoft\RequestModel\RequestModel;
use Yiisoft\RequestModel\ValidatableModelInterface;
use Yiisoft\Validator\Rule\Required;

final class SimpleValidationRequestModel extends RequestModel implements ValidatableModelInterface
{
    public function getLogin(): string
    {
        return (string)$this->getAttributeValue('body.login');
    }

    public function getPassword(): string
    {
        return (string)$this->getAttributeValue('body.password');
    }

    public function getRules(): array
    {
        return [
            'body.login' => [
                Required::rule(),
            ],
            'body.password' => [
                Required::rule(),
            ],
        ];
    }
}
