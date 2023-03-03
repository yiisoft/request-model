<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Concept;

final class TestRequestModel implements ModelInterface
{
    private ?array $rawData = null;

    public ?int $age = null;

    public array $person = [
        'firstName' => '',
    ];

    public ?MyDto $dto = null;

    public function setRawData(array $data): void
    {
        $this->rawData = $data;
    }

    public function getRawData(): array
    {
        return [
            'age' => 'age',
            'first_name' => 'person.first_name',
            'value' => 'dto.value',
        ];
    }
}
