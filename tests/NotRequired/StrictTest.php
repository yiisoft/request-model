<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Tests\NotRequired;

use Yiisoft\RequestModel\RequestModelValidator;
use Yiisoft\RequestModel\Tests\Support\TestCase;

final class StrictTest extends TestCase
{
    public function dataValid(): array
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

    public function dataInvalid(): array
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
     * @dataProvider dataValid
     *
     * @param string|null $expected
     * @param array $data
     */
    public function testValid(?string $expected, array $data): void
    {
        $model = new StrictModel();

        $errors = (new RequestModelValidator())->validate($model, $data);
        $this->assertEmpty($errors);

        $model->setRequestData($data);
        $this->assertSame($expected, $model->getSort());
    }

    /**
     * @dataProvider dataInvalid
     *
     * @param array $data
     */
    public function testInvalid(array $data): void
    {
        $model = new StrictModel();

        $errors = (new RequestModelValidator())->validate($model, $data);
        $this->assertNotEmpty($errors);
    }
}