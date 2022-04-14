<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Tests\Support;

use Yiisoft\RequestModel\RequestModel;
use Yiisoft\Validator\Rule\Required\Required;
use Yiisoft\Validator\RulesProviderInterface;

final class SimpleValidationRequestModel extends RequestModel implements RulesProviderInterface
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
                new Required(),
            ],
            'body.password' => [
                new Required(),
            ],
        ];
    }
}
