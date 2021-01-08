<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Tests\NotRequired;

use Yiisoft\RequestModel\RequestModelValidator;
use Yiisoft\RequestModel\Tests\Support\TestCase;

final class NotStrictTest extends TestCase
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

    public function dataInvalid(): array
    {
        return [
            [
                ['query' => ['sort' => 'up']],
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
        $model = new NotStrictModel();

        $errors = (new RequestModelValidator())->validate($data, $model->getRules());
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
        $model = new NotStrictModel();

        $errors = (new RequestModelValidator())->validate($data, $model->getRules());
        $this->assertNotEmpty($errors);
    }
}
