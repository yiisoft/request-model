<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Tests\Support;

use Yiisoft\RequestModel\RequestModel;

final class SimpleRequestModel extends RequestModel
{
    public function getLogin(): string
    {
        return (string)$this->getValue('body.login');
    }

    public function getPassword(): string
    {
        return (string)$this->getValue('body.password');
    }
}
