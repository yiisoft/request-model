<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Attribute;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Yiisoft\RequestModel\Tests\Attribute\ResolverTestCase;

class RouteResolverTest extends ResolverTestCase
{
    public function dataProvider(): array
    {
        $mockRequest = $this->createMock(ServerRequestInterface::class);
        return [
            [new Route('id'), $mockRequest, 1],
        ];
    }

    public function getResolver(): HandlerParameterResolverInterface
    {
        return new RouteResolver($this->getCurrentRoute());
    }
}
