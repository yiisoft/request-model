<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Tests;

use Yiisoft\RequestModel\Tests\Support\TestCase;
use Yiisoft\RequestModel\RequestModelValidator;
use Yiisoft\Validator\Rule\InRange;
use Yiisoft\Validator\Rule\Number;

class ValidatorTest extends TestCase
{
    public function testValidationCorrectData(): void
    {
        $data = [
            'page' => 100,
            'per_page' => 10,
            'sort' => 'asc'
        ];

        $this->assertEmpty($this->createValidator()->validate($data, $this->getRules()));
    }

    public function testValidationInvalidData(): void
    {
        $data = [
            'page' => 'bad_value',
            'per_page' => 'bad value',
            'sort' => 'bad value'
        ];

        $this->assertEquals(
            [
                'page' => ['Value must be a number.'],
                'per_page' => ['Value must be a number.'],
                'sort' => ['This value is invalid.']
            ],
            $this->createValidator()->validate($data, $this->getRules())
        );
    }

    private function getRules(): array
    {
        return [
            'page' => [
                new Number()
            ],
            'per_page' => [
                new Number()
            ],
            'sort' => [
                new InRange(['asc', 'desc'])
            ],
        ];
    }

    private function createValidator(): RequestModelValidator
    {
        return new RequestModelValidator();
    }
}
