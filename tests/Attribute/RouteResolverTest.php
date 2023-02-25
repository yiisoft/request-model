<?php

declare(strict_types=1);

namespace Yiisoft\RequestModel\Attribute;

use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\RequestModel\Tests\Attribute\ResolverTestCase;
use Yiisoft\RequestModel\YiiRouter\Attribute\Route;
use Yiisoft\RequestModel\YiiRouter\Attribute\RouteResolver;

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
