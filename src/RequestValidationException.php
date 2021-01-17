<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel;

use RuntimeException;
use Generator;

final class RequestValidationException extends RuntimeException
{
    private const MESSAGE = 'Request model validation error';
    private Generator $errors;

    public function __construct(Generator $errors)
    {
        $this->errors = $errors;
        parent::__construct(self::MESSAGE);
    }

    public function getErrors(): array
    {
        return iterator_to_array($this->errors);
    }

    public function getFirstErrors(): ?array
    {
        return $this->errors->current();
    }

    public function getFirstError(): ?string
    {
        $errors = $this->getFirstErrors();

        return null === $errors ? null : reset($errors);
    }
}
