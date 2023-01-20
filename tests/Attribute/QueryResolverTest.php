<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Attribute;

use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\RequestModel\Tests\Attribute\ResolverTestCase;

class QueryResolverTest extends ResolverTestCase
{
    public function dataProvider(): array
    {
        $mockRequest = $this->createMock(ServerRequestInterface::class);
        $mockRequest->method('getQueryParams')->willReturn($data = ['page' => 22]);
        return [
            [new Query(), $mockRequest, $data],
            [new Query('page'), $mockRequest, 22],
        ];
    }

    public function getResolver(): HandlerParameterResolverInterface
    {
        return new QueryResolver();
    }
}
