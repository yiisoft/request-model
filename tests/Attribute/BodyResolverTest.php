<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Attribute;

use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\RequestModel\Tests\Attribute\ResolverTestCase;

class BodyResolverTest extends ResolverTestCase
{
    public function dataProvider(): array
    {
        $mockRequest = $this->createMock(ServerRequestInterface::class);
        $mockRequest->method('getParsedBody')->willReturn($data = ['name' => $name = 'Rustam Mamadaminov']);
        return [
            [new Body(), $mockRequest, $data],
            [new Body('name'), $mockRequest, $name],
        ];
    }

    public function getResolver(): HandlerParameterResolverInterface
    {
        return new BodyResolver();
    }
}
