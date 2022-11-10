<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel;

use RuntimeException;

final class RequestValidationException extends RuntimeException
{
    private const MESSAGE = 'Request model validation error';

    /**
     * @param string[] $errors
     *
     * @psalm-param array<string,string[]> $errors
     */
    public function __construct(
        private array $errors
    ) {
        parent::__construct(self::MESSAGE);
    }

    /**
     * @psalm-return array<string,string[]>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @psalm-return array<string,string>
     */
    public function getFirstErrors(): array
    {
        if (empty($this->errors)) {
            return [];
        }

        $result = [];
        foreach ($this->errors as $name => $errors) {
            if (!empty($errors)) {
                $result[$name] = reset($errors);
            }
        }

        return $result;
    }

    public function getFirstError(): ?string
    {
        $errors = $this->getFirstErrors();

        return $errors === [] ? null : reset($errors);
    }
}
