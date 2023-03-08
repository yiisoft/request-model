<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Tests\Concept;

final class UserModel
{
    public function __construct(
        private NameDto $name
    ) {
    }

    public function getName(): string
    {
        return $this->name->getFirst() . ' ' . $this->name->getLast();
    }
}
