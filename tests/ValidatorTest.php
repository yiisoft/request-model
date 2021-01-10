<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Tests;

use Yiisoft\RequestModel\RequestModelValidator;
use Yiisoft\RequestModel\Tests\Support\NonStrictOptionalRequestModel;
use Yiisoft\RequestModel\Tests\Support\RulesRequestModel;
use Yiisoft\RequestModel\Tests\Support\StrictOptionalRequestModel;
use Yiisoft\RequestModel\Tests\Support\TestCase;
use Yiisoft\Validator\Rule\InRange;
use Yiisoft\Validator\Rule\Number;

class ValidatorTest extends TestCase
{
    public function testValidationCorrectData(): void
    {
        $data = [
            'page' => 100,
            'per_page' => 10,
            'sort' => 'asc',
        ];

        $this->assertEmpty($this->createValidator()->validate($this->getModel(), $data));
    }

    public function testValidationInvalidData(): void
    {
        $data = [
            'page' => 'bad_value',
            'per_page' => 'bad value',
            'sort' => 'bad value',
        ];

        $this->assertEquals(
            [
                'page' => ['Value must be a number.'],
                'per_page' => ['Value must be a number.'],
                'sort' => ['This value is invalid.'],
            ],
            $this->createValidator()->validate($this->getModel(), $data)
        );
    }

    private function getModel(): RulesRequestModel
    {
        return new RulesRequestModel([
            'page' => [
                new Number(),
            ],
            'per_page' => [
                new Number(),
            ],
            'sort' => [
                new InRange(['asc', 'desc']),
            ],
        ]);
    }

    private function createValidator(): RequestModelValidator
    {
        return new RequestModelValidator();
    }

    public function dataNonStrictOptionalValid(): array
    {
        return [
            [
                'asc',
                ['query' => ['sort' => 'asc']],
            ],
            [
                'desc',
                ['query' => ['sort' => 'desc']],
            ],
            [
                null,
                ['query' => ['sort' => '']],
            ],
            [
                null,
                ['query' => []],
            ],
            [
                null,
                [],
            ],
        ];
    }

    /**
     * @dataProvider dataNonStrictOptionalValid
     *
     * @param string|null $expected
     * @param array $data
     */
    public function testNonStrictOptionalValid(?string $expected, array $data): void
    {
        $model = new NonStrictOptionalRequestModel();

        $errors = (new RequestModelValidator())->validate($model, $data);
        $this->assertEmpty($errors);

        $model->setRequestData($data);
        $this->assertSame($expected, $model->getSort());
    }

    public function dataNonStrictOptionalInvalid(): array
    {
        return [
            [
                ['query' => ['sort' => 'up']],
            ],
        ];
    }

    /**
     * @dataProvider dataNonStrictOptionalInvalid
     *
     * @param array $data
     */
    public function testNonStrictOptionalInvalid(array $data): void
    {
        $model = new NonStrictOptionalRequestModel();

        $errors = (new RequestModelValidator())->validate($model, $data);
        $this->assertNotEmpty($errors);
    }

    public function dataStrictOptionalValid(): array
    {
        return [
            [
                'asc',
                ['query' => ['sort' => 'asc']],
            ],
            [
                'desc',
                ['query' => ['sort' => 'desc']],
            ],
            [
                null,
                ['query' => []],
            ],
            [
                null,
                [],
            ],
        ];
    }

    /**
     * @dataProvider dataStrictOptionalValid
     *
     * @param string|null $expected
     * @param array $data
     */
    public function testStrictOptionalValid(?string $expected, array $data): void
    {
        $model = new StrictOptionalRequestModel();

        $errors = (new RequestModelValidator())->validate($model, $data);
        $this->assertEmpty($errors);

        $model->setRequestData($data);
        $this->assertSame($expected, $model->getSort());
    }

    public function dataStrictOptionalInvalid(): array
    {
        return [
            [
                ['query' => ['sort' => 'up']],
            ],
            [
                ['query' => ['sort' => '']],
            ],
        ];
    }

    /**
     * @dataProvider dataNonStrictOptionalInvalid
     *
     * @param array $data
     */
    public function testStrictOptionalInvalid(array $data): void
    {
        $model = new StrictOptionalRequestModel();

        $errors = (new RequestModelValidator())->validate($model, $data);
        $this->assertNotEmpty($errors);
    }
}
