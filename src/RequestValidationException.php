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
        /** @var array<array-key, array>|false $firstErrors */
        $firstErrors = reset($this->errors);

        return $firstErrors !== false ? $firstErrors : null;
    }

    public function getFirstError(): ?string
    {
        /** @var array<array-key, string>|null $errors */
        $errors = $this->getFirstErrors();
        if ($errors === null) {
            return null;
        }
        $firstError = reset($errors);

        return $firstError !== false ? $firstError : null;
    }
}
