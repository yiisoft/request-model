<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Tests;

use Yiisoft\RequestModel\RequestValidationException;
use Yiisoft\RequestModel\Tests\Support\TestCase;

class RequestValidationExceptionTest extends TestCase
{
    public function testGetErrors(): void
    {
        $this->assertEquals(
            [
                'sort' => [
                    'Bad Value',
                    'Wrong type value',
                ],
                'page' => [
                    'Bad value',
                ],
            ],
            $this->createException()->getErrors()
        );
    }

    public function testGetFirstErrors(): void
    {
        $this->assertEquals(
            [
                'Bad Value',
                'Wrong type value',
            ],
            $this->createException()->getFirstErrors()
        );
    }

    public function testGetFirstError(): void
    {
        $this->assertEquals(
            'Bad Value',
            $this->createException()->getFirstError()
        );
    }

    public function testGetEmptyErrors(): void
    {
        $emptyException = new RequestValidationException($this->createGenerator([]));

        $this->assertEquals(null, $emptyException->getFirstError());
    }

    private function createException(): RequestValidationException
    {
        return new RequestValidationException(
            $this->createGenerator(
                [
                    'sort' => [
                        'Bad Value',
                        'Wrong type value',
                    ],
                    'page' => [
                        'Bad value',
                    ],
                ]
            )
        );
    }

    private function createGenerator(array $array): \Generator
    {
        foreach ($array as $key => $value) {
            yield $key => $value;
        }
    }
}
