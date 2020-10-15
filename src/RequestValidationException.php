<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel;

use RuntimeException;

final class RequestValidationException extends RuntimeException
{
    private const MESSAGE = 'Request model validation error';
    private array $errors;

    public function __construct(array $errors)
    {
        $this->errors = $errors;
        parent::__construct(self::MESSAGE);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getFirstErrors(): ?array
    {
        return reset($this->errors);
    }

    public function getFirstError(): ?string
    {
        $errors = $this->getFirstErrors();

        return reset($errors);
    }
}
