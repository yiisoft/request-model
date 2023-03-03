<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Concept;

use Yiisoft\Validator\RuleInterface;

#[RawValidation(
    rules: [
        'age' => new Requred,
    ],
)]
final class Raw
{
    /**
     * @psalm-param list<RuleInterface> $rules
     */
    public function __construct(
        private array $rules,
    ) {
    }

    public function getRules(): string
    {
        return 'raw';
    }

    public function getHandler(): string
    {
        return RawHandler::class;
    }
}
