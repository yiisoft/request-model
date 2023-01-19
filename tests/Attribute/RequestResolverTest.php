<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Attribute;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Yiisoft\RequestModel\Tests\Attribute\ResolverTestCase;

class RequestResolverTest extends ResolverTestCase
{
    public function dataProvider(): array
    {
        $mockRequest = $this->createMock(ServerRequestInterface::class);
        $mockRequest->method('getAttribute')->willReturnCallback(static fn ($name) => $name === 'userId' ? 22 : null);
        return [
            [new Request('userId'), $mockRequest, 22],
        ];
    }

    public function getResolver(): HandlerParameterResolverInterface
    {
        return new RequestResolver();
    }
}
