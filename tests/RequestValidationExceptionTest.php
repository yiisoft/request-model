<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Tests;

use Yiisoft\RequestModel\Tests\Support\TestCase;
use Yiisoft\RequestModel\RequestValidationException;

class RequestValidationExceptionTest extends TestCase
{
    public function testGetErrors(): void
    {
        $this->assertEquals(
            [
                'sort' => [
                    'Bad Value',
                    'Wrong type value'
                ],
                'page' => [
                    'Bad value'
                ]
            ],
            $this->createException()->getErrors()
        );
    }

    public function testGetFirstErrors(): void
    {
        $this->assertEquals(
            [
                'Bad Value',
                'Wrong type value'
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

    private function createException(): RequestValidationException
    {
        return new RequestValidationException(
            [
                'sort' => [
                    'Bad Value',
                    'Wrong type value'
                ],
                'page' => [
                    'Bad value'
                ]
            ]
        );
    }
}
