<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Tests\Support;

use Yiisoft\RequestModel\RequestModel;

final class SimpleRequestModel extends RequestModel
{
    public function getId(): int
    {
        return (int)$this->getAttributeValue('router.id');
    }
    public function getLogin(): string
    {
        return (string)$this->getAttributeValue('body.login');
    }

    public function getPassword(): string
    {
        return (string)$this->getAttributeValue('body.password');
    }
}
